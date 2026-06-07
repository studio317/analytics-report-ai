(function () {
	'use strict';

	function initializeScopeField() {
		var scopeInputs = document.querySelectorAll('[data-analytics-report-ai-scope]');
		var pathField = document.querySelector('[data-analytics-report-ai-path-field]');
		var pathInput = document.querySelector('[data-analytics-report-ai-path-input]');
		var pathDescription = document.querySelector('[data-analytics-report-ai-path-description]');

		if (!scopeInputs.length || !pathField || !pathInput || !pathDescription) {
			return;
		}

		function getSelectedScope() {
			var selected = document.querySelector('[data-analytics-report-ai-scope]:checked');

			return selected ? selected.value : 'site';
		}

		function updatePathField() {
			var scope = getSelectedScope();

			if ('site' === scope) {
				pathField.classList.add('is-hidden');
				pathInput.value = '';
				pathInput.setAttribute('disabled', 'disabled');
				return;
			}

			pathField.classList.remove('is-hidden');
			pathInput.removeAttribute('disabled');

			if ('directory' === scope) {
				pathInput.setAttribute('placeholder', '/blog/');
				pathDescription.textContent = 'For directory scope, enter a path such as /blog/. It will be normalized in the next step.';
				return;
			}

			if ('page' === scope) {
				pathInput.setAttribute('placeholder', '/about');
				pathDescription.textContent = 'For page scope, enter a path such as /about or /about/. It will be normalized in the next step.';
			}
		}

		Array.prototype.forEach.call(scopeInputs, function (input) {
			input.addEventListener('change', updatePathField);
		});

		updatePathField();
	}

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', initializeScopeField);
	} else {
		initializeScopeField();
	}
})();