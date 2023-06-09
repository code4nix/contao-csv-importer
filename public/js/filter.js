'use strict';

function filterProductListingTable(containerId, formId, inputBoxId, rowClass) {
    // Declare variables
    let body, form, button, input, filter, container, tr, td, i, ii, blnShow, txtValue;

    body = document.body;
    form = document.getElementById(formId);
    container = document.getElementById(containerId);
    button = form.querySelector('button[type="submit"]');
    input = document.getElementById(inputBoxId);
    body.setAttribute('searchbusy', 'false');
    tr = container.querySelectorAll('.' + rowClass);

    // Append overlay
    appendOverlay();

    document.getElementById('btnHideDetails').addEventListener('click', e => {
        e.target.disabled = true;
        document.getElementById('btnShowAll').disabled = false;

        // Delete cookie
        const d = new Date();
        d.setTime(d.getTime() - 24 * 60 * 60 * 1000);
        let expires = "expires=" + d.toUTCString();
        let value = 'minimize_table';
        document.cookie = 'display_mode' + "=" + value + ";" + expires + ";path=/";

        let elements = document.querySelectorAll('#filterContainer [data-displaymode]'), i;
        for (i = 0; i < elements.length; ++i) {
            elements[i].setAttribute('data-displaymode', value);
        }

    });

    document.getElementById('btnShowAll').addEventListener('click', e => {
        e.target.disabled = true;
        document.getElementById('btnHideDetails').disabled = false;

        // Set cookie
        const d = new Date();
        d.setTime(d.getTime() + 24 * 60 * 60 * 1000);
        let expires = "expires=" + d.toUTCString();
        let value = 'maximize_table';
        document.cookie = 'display_mode' + "=" + value + ";" + expires + ";path=/";

        let elements = document.querySelectorAll('#filterContainer [data-displaymode]'), i;
        for (i = 0; i < elements.length; ++i) {
            elements[i].setAttribute('data-displaymode', value);
        }
    });

    form.querySelector('[type="reset"]').addEventListener('click', e => {
        document.getElementById('filterSearch').value = '';
        form.submit();
    });

    form.addEventListener("submit", e => {

        (async () => {
            await (() => {
                return new Promise((resolve, reject) => {
                    e.preventDefault();
                    e.stopPropagation();

                    // Add the busy attribute to the body
                    body.setAttribute('searchbusy', 'true');

                    // Disable the button
                    button.setAttribute('disabled', 'true');
                    button.textContent = 'Bitte warten';

                    resolve('Prepare elements.');
                });
            })();

            await (() => {
                return new Promise((resolve, reject) => {
                    window.setTimeout(() => {
                        resolve('Wait for overlay.');
                    }, 100);
                });
            })();

            await (() => {
                return new Promise((resolve, reject) => {

                    // Get the search query text
                    filter = input.value.toUpperCase();

                    // Show all if input is empty
                    if (filter === '') {
                        for (i = 0; i < tr.length; i++) {
                            tr[i].style.display = "";
                        }
                        restore();
                        return;
                    }

                    // Loop through all table rows, and hide those who don't match the search query
                    for (i = 0; i < tr.length; i++) {
                        blnShow = false;
                        td = tr[i].querySelectorAll('td[data-search]');
                        for (ii = 0; ii < td.length; ii++) {

                            txtValue = td[ii].textContent || td[ii].innerText;
                            if (txtValue.toUpperCase().search(filter) > -1) {
                                blnShow = true;
                            }
                        }
                        if (blnShow) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }

                        if (i + 1 === tr.length) {
                            restore();
                        }
                    }
                    resolve('Search complete.');
                });
            })();

            await (() => {
                return new Promise((resolve, reject) => {
                    restore();
                    resolve('restore');
                });
            })();
        })();
    });

    function restore() {
        console.log('restore');
        body.setAttribute('searchbusy', 'false');
        button.removeAttribute('disabled');
        button.textContent = 'Suche starten';
    }

    function appendOverlay() {
        // Append overlay to body
        let overlay = document.createElement('div');
        overlay.id = 'filterSearchOverlay';

        overlay.innerHTML = '<div class="spinner">\n' + '  <div class="rect1"></div>\n' + '  <div class="rect2"></div>\n' + '  <div class="rect3"></div>\n' + '  <div class="rect4"></div>\n' + '  <div class="rect5"></div>\n' + '</div>';

        document.body.appendChild(overlay);
    }

}


