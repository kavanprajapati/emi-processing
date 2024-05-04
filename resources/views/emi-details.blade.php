<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('EMI Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container my-5">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session::get('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                    <form action="{{ route('processEmiData') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary bg-blue">Process EMI Data</button>
                    </form>
                    <br>
                    @if (isset($emis))
                        <div class="row">
                            <h2 class="my-2 fs-4 fw-bold text-center">EMI Details</h2>
                            <div class="table-container" style="overflow-x: auto;">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Client ID</th>
                                            @foreach ($columnNames as $columnName)
                                                <th>{{ $columnName }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($emis as $emi)
                                            <tr>
                                                <td>{{ $emi->client_id }}</td>
                                                @foreach ($columnNames as $columnName)
                                                    <td>{{ $emi->$columnName }}</td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>No Record Found!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $emis->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
