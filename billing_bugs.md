# Bugs Found in Billing Functionality

## 1. Undefined Variable in BillingController store Method
- **Issue**: Line `'customer_id' => $subscription->customer_id ?? null,` uses `$subscription` which is only defined if `subscription_id` is provided. If not, causes undefined variable error.
- **Impact**: Fatal error when creating manual bills without subscription.
- **Fix**: Initialize `$subscription` to null or handle the case properly.

## 2. Inconsistent Invoice Field Usage
- **Issue**: Subscription model's generateInvoice and changePackage methods create invoices with fields like 'customer_contact', 'period_start', 'period_end' that don't exist in the Invoice model.
- **Impact**: Database errors when these methods are called.
- **Fix**: Remove or map these fields to existing Invoice fields.

## 3. Potential Infinite Loop in Invoice getStatusAttribute
- **Issue**: The accessor updates status to 'overdue' which triggers model events, potentially causing recursion.
- **Impact**: Performance issues or infinite loops.
- **Fix**: Use updateQuietly or avoid triggering events in accessors.

## 4. Incorrect Payment Status Check
- **Issue**: Invoice updateStatusFromPayments checks for 'completed' payments, but Payment model might use 'paid' status.
- **Impact**: Incorrect invoice status calculations.
- **Fix**: Check Payment model for correct status values.

## 5. Unused Private Method
- **Issue**: createReconnectionServiceRequest is private but never called in BillingController.
- **Impact**: Dead code.
- **Fix**: Remove or make it public if needed elsewhere.

## 6. Reactivation Logic Bug in markPaid
- **Issue**: Checks `in_array($invoice->subscription->status, ['active', 'pending'])` but then updates service_status. This condition doesn't make sense for reactivation.
- **Impact**: Subscriptions might not be reactivated properly.
- **Fix**: Correct the logic to check if subscription was suspended/disconnected before payment.
