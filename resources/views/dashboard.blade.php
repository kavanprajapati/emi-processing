<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container my-5">
                    <div class="row">
                        <h2 class="my-2 fs-4 fw-bold text-center">Loan Details</h2>
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Client ID</th>
                                    <th scope="col">No. Of Payments</th>
                                    <th scope="col">First Payment Date</th>
                                    <th scope="col">Last Payment Date</th>
                                    <th scope="col">Loan Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($loans as $loan)
                                <tr>
                                    <td>{{ $loan->client_id }}</td>
                                    <td>{{ $loan->num_of_payment }}</td>
                                    <td>{{ $loan->first_payment_date }}</td>
                                    <td>{{ $loan->last_payment_date }}</td>
                                    <td>{{ $loan->loan_amount }}</td>
                                </tr>
                                @empty
                                  <tr><td>No Record Found!</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
