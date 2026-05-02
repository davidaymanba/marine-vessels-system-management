import './bootstrap';
import Chart from 'chart.js/auto';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.notificationBadge = (initialCount, endpoint) => ({
	count: Number(initialCount ?? 0),
	endpoint,
	timer: null,
	async refresh() {
		try {
			const response = await fetch(this.endpoint, {
				headers: {
					Accept: 'application/json',
				},
			});

			if (!response.ok) {
				return;
			}

			const payload = await response.json();
			this.count = Number(payload.count ?? 0);
		} catch (error) {
			// Ignore transient polling failures.
		}
	},
	init() {
		this.refresh();
		this.timer = setInterval(() => this.refresh(), 15000);
	},
});

window.Chart = Chart;

Alpine.start();
