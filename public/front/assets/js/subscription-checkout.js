function initRazorpayCheckout() {
  var forms = document.querySelectorAll('.js-razorpay-checkout');
  if (!forms.length) return;

  function csrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (meta && meta.getAttribute('content')) return meta.getAttribute('content');
    var input = document.querySelector('input[name="_token"]');
    return input ? input.value : '';
  }

  function jsonHeaders() {
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken(),
      'X-Requested-With': 'XMLHttpRequest'
    };
  }

  function toast(message, type) {
    if (typeof showUserToast === 'function') {
      showUserToast(message, type || 'info');
      return;
    }
    alert(message);
  }

  function setLoading(btn, loading) {
    if (!btn) return;
    btn.disabled = !!loading;
    if (loading) {
      if (!btn.dataset.originalText) btn.dataset.originalText = btn.textContent;
      btn.textContent = 'Opening Razorpay...';
    } else if (btn.dataset.originalText) {
      btn.textContent = btn.dataset.originalText;
    }
  }

  function postFailed(url, payload) {
    fetch(url, {
      method: 'POST',
      headers: jsonHeaders(),
      body: JSON.stringify(payload)
    }).catch(function() {});
  }

  async function verifyPayment(url, response, btn) {
    try {
      var res = await fetch(url, {
        method: 'POST',
        headers: jsonHeaders(),
        body: JSON.stringify(response)
      });
      var data = await res.json();
      if (!data.ok) {
        toast(data.message || 'Payment verification failed.', 'error');
        setLoading(btn, false);
        return;
      }
      toast(data.message || 'Payment successful.', 'success');
      window.location.href = data.redirect;
    } catch (err) {
      toast('Payment verification failed. If money was deducted, contact support with your payment ID.', 'error');
      setLoading(btn, false);
    }
  }

  function openRazorpay(checkout, btn) {
    if (typeof Razorpay === 'undefined') {
      toast('Payment script failed to load. Refresh the page and try again.', 'error');
      setLoading(btn, false);
      return;
    }

    var options = {
      key: checkout.key,
      amount: checkout.amount,
      currency: checkout.currency,
      name: checkout.name || 'JustGoom LLP',
      description: checkout.description,
      order_id: checkout.order_id,
      prefill: checkout.prefill || {},
      notes: checkout.notes || {},
      theme: checkout.theme || { color: '#1A428A' },
      method: {
        upi: true,
        card: true,
        netbanking: true,
        wallet: true
      },
      handler: function(response) {
        btn.textContent = 'Confirming payment...';
        verifyPayment(checkout.verify_url, response, btn);
      },
      modal: {
        ondismiss: function() {
          postFailed(checkout.failed_url, {
            razorpay_order_id: checkout.order_id,
            reason: 'Checkout dismissed'
          });
          setLoading(btn, false);
        }
      }
    };

    if (checkout.image) {
      options.image = checkout.image;
    }

    var rzp = new Razorpay(options);
    rzp.on('payment.failed', function(response) {
      var error = response && response.error ? response.error : {};
      postFailed(checkout.failed_url, {
        razorpay_order_id: checkout.order_id,
        razorpay_payment_id: error.metadata && error.metadata.payment_id ? error.metadata.payment_id : null,
        reason: error.description || 'Payment failed'
      });
      toast(error.description || 'Payment failed.', 'error');
      setLoading(btn, false);
    });
    rzp.open();
  }

  forms.forEach(function(form) {
    form.addEventListener('submit', async function(e) {
      e.preventDefault();
      var btn = form.querySelector('button[type="submit"]');
      setLoading(btn, true);
      try {
        var res = await fetch(form.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        var data = await res.json();
        if (!data.ok) {
          toast(data.message || 'Unable to start payment.', 'error');
          setLoading(btn, false);
          return;
        }
        openRazorpay(data.checkout, btn);
      } catch (err) {
        toast('Unable to start payment. Please try again.', 'error');
        setLoading(btn, false);
      }
    });
  });
}

document.addEventListener('DOMContentLoaded', initRazorpayCheckout);
