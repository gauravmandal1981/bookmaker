document.addEventListener('DOMContentLoaded', function () {

            const repeater =
                document.getElementById('url-repeater');

            document
                .getElementById('add-url')
                .addEventListener('click', function () {

                    const row =
                        document.createElement('div');

                    row.className = 'url-row';
                    row.style.marginBottom = '10px';

                    row.innerHTML = `
                        <input
                            type="url"
                            name="bookmaker_urls[]"
                            class="regular-text"
                            placeholder="Bookmarker URL"
                        >

                        <button
                            type="button"
                            class="button remove-url"
                        >
                            Delete
                        </button>
                    `;

                    repeater.appendChild(row);
                });

            document.addEventListener('click', function (e) {

                if (
                    e.target.classList.contains('remove-url')
                ) {

                    const rows =
                        document.querySelectorAll('.url-row');

                    if (rows.length > 1) {
                        e.target.closest('.url-row').remove();
                    } else {
                        e.target
                            .closest('.url-row')
                            .querySelector('input')
                            .value = '';
                    }
                }
            });

        });