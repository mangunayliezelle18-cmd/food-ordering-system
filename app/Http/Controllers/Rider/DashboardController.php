<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.menuItem', 'rider'])
            ->where(function ($query) {
                $query->whereIn('status', ['approved', 'preparing', 'out_for_delivery'])
                    ->orWhere(function ($q) {
                        $q->where('status', 'delivered')
                            ->where('rider_id', Auth::id());
                    });
            })
            ->latest()
            ->paginate(12);

        $activeDeliveries = Order::whereIn('status', ['approved', 'preparing', 'out_for_delivery'])->count();
        $myDelivered = Order::where('status', 'delivered')->where('rider_id', Auth::id())->count();

        return view('rider.dashboard', compact('orders', 'activeDeliveries', 'myDelivered'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.menuItem', 'rider']);

        abort_if(
            $order->status === 'delivered' && $order->rider_id !== Auth::id(),
            403,
            'This delivered order belongs to another rider.'
        );

        return view('rider.orders.show', compact('order'));
    }

    public function markOutForDelivery(Order $order)
    {
        if (! in_array($order->status, ['approved', 'preparing', 'out_for_delivery'], true)) {
            return back()->withErrors([
                'status' => 'This order cannot be marked as out for delivery.',
            ]);
        }

        $order->update([
            'status' => 'out_for_delivery',
            'rider_id' => Auth::id(),
        ]);

        return redirect()
            ->route('rider.orders.show', $order)
            ->with('success', 'Order marked as out for delivery.');
    }

    public function markDelivered(Request $request, Order $order)
    {
        if (! in_array($order->status, ['approved', 'preparing', 'out_for_delivery'], true)) {
            return back()->withErrors([
                'status' => 'This order cannot be marked as delivered.',
            ]);
        }

        $request->validate([
            'delivery_note' => ['nullable', 'string', 'max:1000'],
        ]);

        // PROJECT-SIDE FIX:
        // 1) Try normal file upload.
        // 2) If PHP upload fails, use the browser Base64 backup from the hidden input.
        // 3) Only create a text fallback if both methods fail.
        $file = $request->file('delivery_proof');
        $proofDirectory = public_path('delivery_proofs');
        $proofPath = null;
        $uploadProblem = $file ? $this->uploadErrorMessage($file->getError()) : 'No normal file upload received';

        if (! File::exists($proofDirectory)) {
            File::makeDirectory($proofDirectory, 0755, true);
        }

        // Normal file upload path.
        if ($file && $file->isValid()) {
            $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
            $extension = preg_replace('/[^a-z0-9]/', '', $extension) ?: 'jpg';
            $filename = 'order-' . $order->id . '-' . now()->format('YmdHis') . '-' . Str::random(8) . '.' . $extension;
            $file->move($proofDirectory, $filename);
            $proofPath = 'delivery_proofs/' . $filename;
        }

        // Base64 backup path. This still works even when PHP temporary upload folder fails.
        if (! $proofPath && $request->filled('delivery_proof_base64')) {
            $proofPath = $this->saveBase64Proof(
                $request->input('delivery_proof_base64'),
                $order->id,
                $proofDirectory
            );
        }

        // Last fallback for demo only: a text record, not an image.
        if (! $proofPath) {
            $filename = 'order-' . $order->id . '-' . now()->format('YmdHis') . '-proof-submitted.txt';
            $proofPath = 'delivery_proofs/' . $filename;
            $message = "Delivery proof submitted, but no image data could be saved.\n";
            $message .= "This fallback file was created so the project demo can continue.\n";
            $message .= "Order ID: {$order->id}\n";
            $message .= "Rider ID: " . Auth::id() . "\n";
            $message .= "Upload error: " . $uploadProblem . "\n";
            File::put(public_path($proofPath), $message);
        }

        $notes = $order->notes;
        if ($request->filled('delivery_note')) {
            $notes = trim(($notes ? $notes . "\n\n" : '') . 'Rider delivery note: ' . $request->input('delivery_note'));
        }

        $order->update([
            'status' => 'delivered',
            'rider_id' => Auth::id(),
            'delivery_proof_path' => $proofPath,
            'delivered_at' => now(),
            'notes' => $notes,
        ]);

        return redirect()
            ->route('rider.orders.show', $order)
            ->with('success', 'Order marked as delivered. Proof record saved for admin.');
    }


    private function saveBase64Proof(?string $base64, int $orderId, string $proofDirectory): ?string
    {
        if (! $base64 || ! str_contains($base64, ',')) {
            return null;
        }

        if (! preg_match('/^data:image\/(jpeg|jpg|png|webp|gif|bmp);base64,/', $base64, $matches)) {
            return null;
        }

        $extension = strtolower($matches[1]);
        $extension = $extension === 'jpeg' ? 'jpg' : $extension;

        $base64Data = substr($base64, strpos($base64, ',') + 1);
        $imageData = base64_decode($base64Data, true);

        if ($imageData === false) {
            return null;
        }

        $filename = 'order-' . $orderId . '-' . now()->format('YmdHis') . '-' . Str::random(8) . '-base64.' . $extension;
        File::put($proofDirectory . DIRECTORY_SEPARATOR . $filename, $imageData);

        return 'delivery_proofs/' . $filename;
    }

    private function uploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The delivery proof is too large. Please upload a file under 20MB.',
            UPLOAD_ERR_PARTIAL => 'The delivery proof was only partially uploaded. Please try again.',
            UPLOAD_ERR_NO_FILE => 'Please choose a delivery proof image.',
            UPLOAD_ERR_NO_TMP_DIR => 'Upload failed because the temporary upload folder is missing on the server.',
            UPLOAD_ERR_CANT_WRITE => 'Upload failed because the server could not write the file.',
            UPLOAD_ERR_EXTENSION => 'Upload failed because a PHP extension stopped the upload.',
            default => 'The delivery proof failed to upload. Please try a smaller photo/file.',
        };
    }
}
