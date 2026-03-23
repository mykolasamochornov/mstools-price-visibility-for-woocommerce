document.addEventListener('DOMContentLoaded', function() {
	const btn = document.querySelector('#ppvfw-save-btn');
	const form = document.querySelector('#ppvfw-settings-form');

	if (!btn || !form) return;

	let noticeWrapper = null;

	function showNotice(message, type = 'success') {
		if (!noticeWrapper) {
			noticeWrapper = document.createElement('div');
			const wrap = document.querySelector('.wrap');
			if (wrap) wrap.prepend(noticeWrapper);
		}

		noticeWrapper.className = `notice notice-${type} is-dismissible`;
		noticeWrapper.innerHTML = `<p>${message}</p>`;

		const dismiss = noticeWrapper.querySelector('.notice-dismiss');
		if (dismiss) dismiss.addEventListener('click', () => noticeWrapper.remove());

		setTimeout(() => {
			if (noticeWrapper) noticeWrapper.remove();
			noticeWrapper = null;
		}, 3000);
	}

	btn.addEventListener('click', function(e) {
		e.preventDefault();

		const data = new FormData();
		data.append('action', 'ppvfw_save_settings');
		data.append('nonce', ppvfw_ajax.nonce);

		const fields = form.querySelectorAll('[name]');
		fields.forEach(field => {
			let value;
			if (field.type === 'checkbox') {
				value = field.checked ? 1 : 0;
			} else {
				value = field.value;
			}

			data.append(`settings[${field.name}]`, value);
		});

		fetch(ppvfw_ajax.ajax_url, {
			method: 'POST',
			body: data
		})
		.then(r => r.json())
		.then(res => {
			if(res.success) {
				showNotice('Settings saved!', 'success');
			} else {
				showNotice('Error saving settings!', 'error');
			}
		})
		.catch(() => showNotice('Error saving settings!', 'error'));
	});
});