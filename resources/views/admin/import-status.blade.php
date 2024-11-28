<x-app-layout>
    <x-admin.side-bar></x-admin.side-bar>
    <div class="grid grid-cols-12 text-gray-900 min-h-fit pr-4">
        <div class="col-start-3 col-end-13">
            <div class="p-4 flex justify-between items-center">
                <h1 class="text-3xl">Imports</h1>

            </div>
            <div class="px-3 py-4 flex flex-col justify-between h-3/4">
                <table class="w-full text-md bg-white shadow-md rounded mb-4 border-t-2">
                    <thead>
                    <tr class="border-b">
                        <th class="text-left p-3 px-5  w-[10%]">
                            <div class="inline-block">Status</div>
                        </th>
                        <th class="text-left p-3 px-5  w-[40%]">
                            <div class="inline-block">Message</div>
                        </th>
                        <th class="text-left p-3 px-5  w-[20%]">
                            <div class="inline-block">Last updated at</div>
                        </th>
                        <th class="text-left p-3 px-5  w-[20%]">
                            <div class="inline-block">Created at</div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <div id="paginationControls" class="flex gap-1"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const apiUrl2 = "http://localhost:8000/admin/api/import-status";
                let importStatus = [];
                let pageSize = 6;
                let currentPage = 1;

                async function fetchData() {
                    try {
                        const response = await fetch(apiUrl2, {
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        });
                        importStatus = await response.json()

                        if (!response.ok) {
                            console.error('Error fetching import statuses:', data.error);
                            return;
                        }

                        displayData();
                        setupPagination();
                    } catch (error) {
                    console.error('Error fetching import statuses:', error);
                    }
                }

                function displayData() {
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';

                    const start = (currentPage - 1) * pageSize
                    const end = start + pageSize

                    const paginatedData = importStatus.slice(start, end)

                    paginatedData.forEach(importStatus => {
                        const trow = document.createElement('tr');
                        trow.className = 'border-b bg-gray-100';

                        trow.innerHTML = `
                            <td class="p-3 px-5 ${importStatus.status === 'failed' ? 'text-red-500' :
                                            (importStatus.status === 'done' ? 'text-green-500' : 'text-yellow-500')
                                        } ">${importStatus.status || 'N/A'}</td>
                            <td class="p-3 px-5">${importStatus.message || 'No message'}</td>
                            <td class="p-3 px-5">${importStatus.updated_at? formatDate(importStatus.updated_at) : ''}</td>
                            <td class="p-3 px-5">${importStatus.created_at? formatDate(importStatus.created_at) : ''}</td>
                        `;

                        tbody.appendChild(trow);
                    });
                }

                function setupPagination() {
                        const paginationControls = document.getElementById('paginationControls');
                        paginationControls.innerHTML = ''; // Clear existing pagination buttons

                        const totalPages = Math.ceil(importStatus.length / pageSize);

                        for (let i = 1; i <= totalPages; i++) {
                            const button = document.createElement('button');
                            button.textContent = i;
                            button.classList.add('px-2');
                            button.classList.add('py-1');
                            button.classList.add('rounded');
                            button.classList.add('bg-blue-500');
                            button.classList.add('text-white');

                            if (i === currentPage) {
                                button.classList.add('active'); // Highlight the current page
                            }

                            button.addEventListener('click', () => {
                                currentPage = i;
                                displayData(); // Display the students for the selected page
                            });

                            paginationControls.appendChild(button);
                        }
                    }

                function formatDate(isoDateString) {
                    const date = new Date(isoDateString);
                    return date.toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'numeric',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                }
                
                fetchData();

                setInterval(fetchData, 5000);
            });
        </script>
    @endpush
</x-app-layout>
