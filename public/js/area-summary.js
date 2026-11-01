document.addEventListener('DOMContentLoaded', () => {
  const select = document.querySelector('#area-select');
  const output = document.querySelector('#area-summary');

  if (!select || !output) {
    return;
  }

  select.addEventListener('change', async () => {
    if (!select.value) {
      output.textContent = '';
      return;
    }

    output.textContent = 'Memuat informasi wilayah...';

    const url = select.dataset.urlTemplate
      .replace('__AREA__', encodeURIComponent(select.value));

    try {
      const response = await fetch(url, {
        headers: { Accept: 'application/json' },
      });

      if (!response.ok) {
        throw new Error('Request gagal');
      }

      const data = await response.json();
      output.textContent = `${data.area}: ${data.active_reports_count} laporan aktif`;
    } catch {
      output.textContent = 'Informasi wilayah gagal dimuat.';
    }
  });
});
