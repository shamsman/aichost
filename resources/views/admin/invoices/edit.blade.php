@extends('layouts.app')

@section('title', 'Edit Invoice ' . $invoice->invoice_number . ' — Super Admin')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 90px; max-width: 1080px;">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">
            <a href="{{ route('admin.invoices.index') }}" style="color: var(--accent-cyan); text-decoration: none;">&larr; Invoices Management</a>
            <span>/</span>
            <a href="{{ route('admin.invoices.show', $invoice->id) }}" style="color: var(--accent-cyan); text-decoration: none;">{{ $invoice->invoice_number }}</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">Edit Invoice {{ $invoice->invoice_number }}</h1>
                <p style="color: var(--text-muted); font-size: 14px;">Modify billable items, customer info, or update payment status.</p>
            </div>
            <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-outline btn-sm">
                View Receipt Preview &rarr;
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-banner alert-error" style="margin-bottom: 24px;">
            <div>
                <strong style="display: block; margin-bottom: 4px;">Please review the following errors:</strong>
                <ul style="padding-left: 18px; font-size: 13px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.invoices.update', $invoice->id) }}" id="invoiceForm">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">
            <!-- Client Selection & Details -->
            <div class="glass-panel" style="padding: 28px;">
                <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                    <span>👤</span> Client Information
                </h3>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">
                        Linked Registered User
                    </label>
                    <select id="user_select" name="user_id" style="width: 100%;" onchange="onUserSelect(this)">
                        <option value="">-- Custom / Guest Client --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" 
                                data-name="{{ $u->name }}" 
                                data-email="{{ $u->email }}" 
                                data-company="{{ $u->company }}"
                                data-address="{{ $u->address }}"
                                data-phone="{{ $u->phone }}"
                                {{ old('user_id', $invoice->user_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Client Name *</label>
                        <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $invoice->recipient_name) }}" style="width: 100%;" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Client Email *</label>
                        <input type="email" id="client_email" name="client_email" value="{{ old('client_email', $invoice->recipient_email) }}" style="width: 100%;" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Company</label>
                        <input type="text" id="client_company" name="client_company" value="{{ old('client_company', $invoice->recipient_company) }}" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Phone</label>
                        <input type="text" id="client_phone" name="client_phone" value="{{ old('client_phone', $invoice->client_phone) }}" style="width: 100%;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Billing Address</label>
                    <textarea id="client_address" name="client_address" rows="2" style="width: 100%;">{{ old('client_address', $invoice->recipient_address) }}</textarea>
                </div>
            </div>

            <!-- Invoice Metadata -->
            <div class="glass-panel" style="padding: 28px;">
                <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                    <span>📋</span> Invoice Settings
                </h3>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Invoice Number *</label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" style="width: 100%; font-family: monospace; font-weight: 700; color: var(--primary);" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Currency</label>
                        <select name="currency" style="width: 100%;">
                            <option value="USD" {{ old('currency', $invoice->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency', $invoice->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ old('currency', $invoice->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Status</label>
                        <select name="status" style="width: 100%;">
                            <option value="unpaid" {{ old('status', $invoice->status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Due Date</label>
                        <input type="date" name="due_at" value="{{ old('due_at', $invoice->due_at?->format('Y-m-d')) }}" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Paid Date</label>
                        <input type="date" name="paid_at" value="{{ old('paid_at', $invoice->paid_at?->format('Y-m-d')) }}" style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Line Items Section -->
        <div class="glass-panel" style="padding: 28px; margin-bottom: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">Invoice Line Items</h3>
                    <p style="color: var(--text-muted); font-size: 13px;">Manage billable products, servers, and custom line items.</p>
                </div>
                <button type="button" class="btn btn-outline btn-sm" onclick="addItemRow()">
                    + Add Item Line
                </button>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--card-border); color: var(--text-muted);">
                            <th style="padding: 10px 12px; width: 22%;">Service / Item Type</th>
                            <th style="padding: 10px 12px; width: 42%;">Description</th>
                            <th style="padding: 10px 12px; width: 12%;">Qty</th>
                            <th style="padding: 10px 12px; width: 14%;">Unit Price ($)</th>
                            <th style="padding: 10px 12px; width: 10%; text-align: right;">Total</th>
                            <th style="padding: 10px 4px; width: 30px;"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsContainer">
                        @forelse($invoice->items as $idx => $item)
                            <tr class="item-row" style="border-bottom: 1px solid var(--card-border);">
                                <td style="padding: 12px 8px;">
                                    <select name="items[{{ $idx }}][item_type]" style="width: 100%;" required>
                                        <option value="vps" {{ $item->item_type == 'vps' ? 'selected' : '' }}>Google Cloud VPS</option>
                                        <option value="hosting" {{ $item->item_type == 'hosting' ? 'selected' : '' }}>CWP Web Hosting</option>
                                        <option value="domain" {{ $item->item_type == 'domain' ? 'selected' : '' }}>Domain Registration</option>
                                        <option value="custom_service" {{ $item->item_type == 'custom_service' ? 'selected' : '' }}>Custom Cloud Service</option>
                                        <option value="setup_fee" {{ $item->item_type == 'setup_fee' ? 'selected' : '' }}>Setup / Migration Fee</option>
                                        <option value="ssl" {{ $item->item_type == 'ssl' ? 'selected' : '' }}>SSL / Security Certificate</option>
                                    </select>
                                </td>
                                <td style="padding: 12px 8px;">
                                    <input type="text" name="items[{{ $idx }}][description]" value="{{ old("items.{$idx}.description", $item->description) }}" placeholder="Item description" style="width: 100%;" required>
                                </td>
                                <td style="padding: 12px 8px;">
                                    <input type="number" step="0.01" min="0.01" name="items[{{ $idx }}][quantity]" value="{{ old("items.{$idx}.quantity", $item->quantity) }}" class="item-qty" style="width: 100%;" oninput="calculateTotals()" required>
                                </td>
                                <td style="padding: 12px 8px;">
                                    <input type="number" step="0.01" min="0" name="items[{{ $idx }}][unit_price]" value="{{ old("items.{$idx}.unit_price", $item->unit_price) }}" class="item-price" style="width: 100%;" oninput="calculateTotals()" required>
                                </td>
                                <td style="padding: 12px 8px; text-align: right; font-weight: 700; color: var(--text-main);" class="row-total">
                                    ${{ number_format($item->line_total, 2) }}
                                </td>
                                <td style="padding: 12px 4px; text-align: center;">
                                    <button type="button" onclick="removeItemRow(this)" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 18px;" title="Remove row">&times;</button>
                                </td>
                            </tr>
                        @empty
                            <tr class="item-row" style="border-bottom: 1px solid var(--card-border);">
                                <td style="padding: 12px 8px;">
                                    <select name="items[0][item_type]" style="width: 100%;" required>
                                        <option value="vps">Google Cloud VPS</option>
                                        <option value="hosting" selected>CWP Web Hosting</option>
                                        <option value="domain">Domain Registration</option>
                                        <option value="custom_service">Custom Cloud Service</option>
                                        <option value="setup_fee">Setup / Migration Fee</option>
                                    </select>
                                </td>
                                <td style="padding: 12px 8px;">
                                    <input type="text" name="items[0][description]" value="Cloud Hosting Plan" placeholder="Item description" style="width: 100%;" required>
                                </td>
                                <td style="padding: 12px 8px;">
                                    <input type="number" step="0.01" min="0.01" name="items[0][quantity]" value="1" class="item-qty" style="width: 100%;" oninput="calculateTotals()" required>
                                </td>
                                <td style="padding: 12px 8px;">
                                    <input type="number" step="0.01" min="0" name="items[0][unit_price]" value="{{ $invoice->subtotal }}" class="item-price" style="width: 100%;" oninput="calculateTotals()" required>
                                </td>
                                <td style="padding: 12px 8px; text-align: right; font-weight: 700; color: var(--text-main);" class="row-total">
                                    ${{ number_format($invoice->subtotal, 2) }}
                                </td>
                                <td style="padding: 12px 4px; text-align: center;">
                                    <button type="button" onclick="removeItemRow(this)" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 18px;" title="Remove row">&times;</button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 14px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="addItemRow()">
                    + Add Another Item
                </button>
            </div>
        </div>

        <!-- Calculations & Summary -->
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; margin-bottom: 32px;">
            <!-- Notes & Terms -->
            <div class="glass-panel" style="padding: 24px;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Notes to Client</label>
                    <textarea name="notes" rows="3" placeholder="Additional notes or payment instructions..." style="width: 100%;">{{ old('notes', $invoice->notes) }}</textarea>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Terms & Conditions</label>
                    <textarea name="terms" rows="2" style="width: 100%;">{{ old('terms', $invoice->terms) }}</textarea>
                </div>
            </div>

            <!-- Totals calculation panel -->
            <div class="glass-panel" style="padding: 24px; background: var(--surface-hover);">
                <h4 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; border-bottom: 1px solid var(--card-border); padding-bottom: 10px;">
                    Financial Summary
                </h4>

                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                    <span style="color: var(--text-muted);">Subtotal:</span>
                    <strong style="color: var(--text-main);" id="subtotalDisplay">${{ number_format($invoice->subtotal, 2) }}</strong>
                </div>

                <!-- Discount -->
                <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 12px;">
                    <select name="discount_type" id="discount_type" style="padding: 6px 8px; font-size: 12px; flex: 1;" onchange="calculateTotals()">
                        <option value="none" {{ old('discount_type', $invoice->discount_type) == 'none' ? 'selected' : '' }}>No Discount</option>
                        <option value="fixed" {{ old('discount_type', $invoice->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed ($)</option>
                        <option value="percent" {{ old('discount_type', $invoice->discount_type) == 'percent' ? 'selected' : '' }}>Percent (%)</option>
                    </select>
                    <input type="number" step="0.01" min="0" name="discount_value" id="discount_value" value="{{ old('discount_value', $invoice->discount_value) }}" placeholder="Value" style="width: 80px; padding: 6px 8px; font-size: 12px;" oninput="calculateTotals()">
                    <span id="discountDisplay" style="color: #ef4444; font-size: 13px; font-weight: 600; min-width: 60px; text-align: right;">-${{ number_format($invoice->discount_amount, 2) }}</span>
                </div>

                <!-- Tax -->
                <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                    <span style="font-size: 12px; color: var(--text-muted); flex: 1;">Tax Rate (%):</span>
                    <input type="number" step="0.01" min="0" max="100" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $invoice->tax_rate) }}" placeholder="Tax %" style="width: 80px; padding: 6px 8px; font-size: 12px;" oninput="calculateTotals()">
                    <span id="taxDisplay" style="color: var(--text-main); font-size: 13px; font-weight: 600; min-width: 60px; text-align: right;">+${{ number_format($invoice->tax_amount, 2) }}</span>
                </div>

                <div style="border-top: 2px solid var(--card-border); padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 800; font-size: 16px; color: var(--text-main);">Grand Total:</span>
                    <span style="font-size: 26px; font-weight: 800; color: var(--primary); font-family: 'Space Grotesk', sans-serif;" id="grandTotalDisplay">
                        ${{ number_format($invoice->total, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; justify-content: flex-end; gap: 14px; align-items: center;">
            <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-outline">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary" style="padding: 14px 32px; font-size: 15px;">
                Update Invoice
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let rowIndex = {{ max(1, count($invoice->items)) }};

    function onUserSelect(select) {
        const option = select.options[select.selectedIndex];
        if (select.value) {
            document.getElementById('client_name').value = option.dataset.name || '';
            document.getElementById('client_email').value = option.dataset.email || '';
            document.getElementById('client_company').value = option.dataset.company || '';
            document.getElementById('client_address').value = option.dataset.address || '';
            document.getElementById('client_phone').value = option.dataset.phone || '';
        }
    }

    function addItemRow() {
        const tbody = document.getElementById('itemsContainer');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.style.borderBottom = '1px solid var(--card-border)';
        
        tr.innerHTML = `
            <td style="padding: 12px 8px;">
                <select name="items[${rowIndex}][item_type]" style="width: 100%;" required>
                    <option value="vps">Google Cloud VPS</option>
                    <option value="hosting">CWP Web Hosting</option>
                    <option value="domain">Domain Registration</option>
                    <option value="custom_service" selected>Custom Cloud Service</option>
                    <option value="setup_fee">Setup / Migration Fee</option>
                    <option value="ssl">SSL / Security Certificate</option>
                </select>
            </td>
            <td style="padding: 12px 8px;">
                <input type="text" name="items[${rowIndex}][description]" placeholder="Item description" style="width: 100%;" required>
            </td>
            <td style="padding: 12px 8px;">
                <input type="number" step="0.01" min="0.01" name="items[${rowIndex}][quantity]" value="1" class="item-qty" style="width: 100%;" oninput="calculateTotals()" required>
            </td>
            <td style="padding: 12px 8px;">
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][unit_price]" value="0.00" class="item-price" style="width: 100%;" oninput="calculateTotals()" required>
            </td>
            <td style="padding: 12px 8px; text-align: right; font-weight: 700; color: var(--text-main);" class="row-total">
                $0.00
            </td>
            <td style="padding: 12px 4px; text-align: center;">
                <button type="button" onclick="removeItemRow(this)" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 18px;" title="Remove row">&times;</button>
            </td>
        `;
        
        tbody.appendChild(tr);
        rowIndex++;
        calculateTotals();
    }

    function removeItemRow(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length <= 1) {
            alert('Invoice must have at least one line item.');
            return;
        }
        btn.closest('tr').remove();
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        const rows = document.querySelectorAll('.item-row');
        
        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const lineTotal = qty * price;
            row.querySelector('.row-total').textContent = '$' + lineTotal.toFixed(2);
            subtotal += lineTotal;
        });

        const discType = document.getElementById('discount_type').value;
        const discVal = parseFloat(document.getElementById('discount_value').value) || 0;
        let discAmount = 0;

        if (discType === 'fixed') {
            discAmount = Math.min(discVal, subtotal);
        } else if (discType === 'percent') {
            discAmount = subtotal * (discVal / 100);
        }

        const taxable = Math.max(0, subtotal - discAmount);
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const taxAmount = taxable * (taxRate / 100);
        const grandTotal = taxable + taxAmount;

        document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('discountDisplay').textContent = '-$' + discAmount.toFixed(2);
        document.getElementById('taxDisplay').textContent = '+$' + taxAmount.toFixed(2);
        document.getElementById('grandTotalDisplay').textContent = '$' + grandTotal.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateTotals();
    });
</script>
@endsection
