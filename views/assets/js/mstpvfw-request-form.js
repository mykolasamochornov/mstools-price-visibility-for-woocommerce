document.addEventListener('DOMContentLoaded', function () {
	const forms = document.querySelectorAll('.mstpvfw-request-form');

	if (!forms.length) return;

	forms.forEach(function (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();

			const submitButton = form.querySelector('button[type="submit"]');
			const emailInput = form.querySelector('input[name="mstpvfw_email"]');
			const productId = form.getAttribute('data-product-id');

			if (!emailInput.value) {
				alert('Please enter your email');
				return;
			}

			// Disable button during request
			submitButton.disabled = true;
			const originalText = submitButton.textContent;
			submitButton.textContent = 'Sending...';

			const data = new FormData();
			data.append('action', 'mstpvfw_create_request_order');
			data.append('nonce', mstpvfw_ajax.nonce);
			data.append('email', emailInput.value);
			data.append('product_id', productId);

			fetch(mstpvfw_ajax.ajax_url, {
				method: 'POST',
				body: data,
			})
				.then((response) => response.json())
				.then((res) => {
					if (res.success) {
						// Success message
						alert(res.data.message || 'Request submitted successfully');

						// Reset form
						form.reset();
					} else {
						// Error message
						alert(res.data?.message || 'Error submitting request');
					}
				})
				.catch(() => {
					alert('Error submitting request');
				})
				.finally(() => {
					submitButton.disabled = false;
					submitButton.textContent = originalText;
				});
		});
	});
});