@php $detail = isset($order) ? $order->details->first() : null; @endphp
<div class="form-row">
    <div class="form-group col-md-6"><label for="invoice_number">Invoice number</label><input id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $order->invoice_number ?? '') }}" class="form-control" maxlength="30" required></div>
    <div class="form-group col-md-6"><label for="customer_id">Customer</label><select id="customer_id" name="customer_id" class="form-control" required>@foreach($customers as $customer)<option value="{{ $customer->id }}" {{ (string)old('customer_id', $order->customer_id ?? '') === (string)$customer->id ? 'selected' : '' }}>{{ $customer->customer_number }} - {{ $customer->name }}</option>@endforeach</select></div>
</div>
<div class="form-row">
    <div class="form-group col-md-6"><label for="order_date">Order date</label><input id="order_date" type="datetime-local" name="order_date" value="{{ old('order_date', isset($order) ? $order->order_date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" class="form-control" required></div>
    <div class="form-group col-md-6"><label for="status">Status</label><select id="status" name="status" class="form-control" required>@foreach($statuses as $status)<option value="{{ $status }}" {{ old('status', $order->status ?? 'Ordered') === $status ? 'selected' : '' }}>{{ $status }}</option>@endforeach</select></div>
</div>
<div class="form-group"><label for="delivery_address">Delivery address</label><input id="delivery_address" name="delivery_address" value="{{ old('delivery_address', $order->delivery_address ?? '') }}" class="form-control" maxlength="255" required></div>
<div class="form-row">
    <div class="form-group col-md-8"><label for="material_id">Material</label><select id="material_id" name="material_id" class="form-control" required>@foreach($materials as $material)<option value="{{ $material->id }}" {{ (string)old('material_id', optional($detail)->material_id) === (string)$material->id ? 'selected' : '' }}>{{ $material->name }} (stock: {{ $material->stock }})</option>@endforeach</select></div>
    <div class="form-group col-md-4"><label for="quantity">Quantity</label><input id="quantity" type="number" min="1" name="quantity" value="{{ old('quantity', optional($detail)->quantity ?? 1) }}" class="form-control" required></div>
</div>
<div class="form-group"><label for="notes">Notes</label><textarea id="notes" name="notes" class="form-control" rows="3">{{ old('notes', $order->notes ?? '') }}</textarea></div>
<div class="form-group"><label for="evidence_photo">Photographic evidence</label><input id="evidence_photo" type="file" name="evidence_photo" class="form-control-file" accept="image/jpeg,image/png,image/webp" capture="environment"><small class="form-text text-muted">Optional. JPG, PNG or WebP, maximum 5 MB. Allowed only for In Route or Delivered.</small></div>
<button class="btn btn-primary" type="submit">Save order</button> <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
