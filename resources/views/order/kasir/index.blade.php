@extends('layouts.template', ['title' => 'Pembelian'])

@section('content')
    <div class="container mt-3" style="margin-bottom: 20px">
        @if (Auth::user()->role == 'admin')
            <div class="d-flex justify-content-end">
                <a href="{{ Route('kasir.order.create') }}" class="btn btn-primary">Exel</a>
            </div>
        @else
            <div class="d-flex justify-content-end">
                <a href="{{ Route('kasir.order.create') }}" class="btn btn-primary">Pembelian Baru</a>
            </div>
        @endif
        {{-- @if (Auth::user()->role == 'admin')
            <div class="d-flex justify-content-end">
                <a href="{{ Route('kasir.order.create') }}" class="btn btn-primary">Exel</a>
            </div>
        @endif
        @if (Auth::user()->role == 'kasir')
            <div class="d-flex justify-content-end">
                <a href="{{ Route('kasir.order.create') }}" class="btn btn-primary">Pembelian Baru</a>
            </div>
        @endif --}}
        <form action="{{ route('kasir.order.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-2">
                    <input type="date" name="search_date" class="form-control" value="{{ request('search_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary ms-2">Cari</button>
                    <a href="{{ route('kasir.order.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pembeli</th>
                    <th>Obat</th>
                    <th>Total Bayar</th>
                    <th>Waktu</th>
                    <th>Role</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($orders as $item)
                    {{-- @php
            dd($item['name_customer']);
            @endphp --}}
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item['name_customer'] }}</td>
                        {{-- karna column medicines pada table orders bertipe json yang diubah formatnya menjadi array, maka dari itu untuk mengakses/menampilkan item nya perlu menggunakan looping --}}
                        <td>
                            @foreach ($item['medicines'] as $medicine)
                                <ol>
                                    <li>
                                        {{-- mengakses ke array assoc dari tiap item array value colum medicines --}}
                                        {{ $medicine['name_medicine'] }}
                                        ({{ number_format($medicine['price'], 0, ',', '.') }}) : Rp. {{ number_format($medicine['sub_price'], 0, ',', '.') }} <small>qty {{ $medicine['qty'] }}</small>
                                    </li>
                                </ol>
                            @endforeach
                        </td>

                        <td>{{ number_format($item['total_price'], 0, ',', '.') }}</td>

                        {{-- <td>{{ number_fotmat($item['total_price'],0,',','.') }}</td> --}}
                        {{-- karna nama kasir terdapat pada tabel users, dan relasi antara --}}
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item['user']['name'] }}</td>
                        <td>
                            <a href="{{ route('kasir.order.download', $item->id) }}" class="btn btn-secondary"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-download" viewBox="0 0 16 16">
                                    <path
                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                    <path
                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                </svg> Download Setruk</a>
                            {{-- <a href="{{ route('medicine.edit', $item['id']) }}" class="btn btn-primary me-3">Edit</a>
                        <form action="{{ route('medicine.delete', $item['id']) }}" method="POST"
                        onsubmit="return confirm('serous')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            @if ($orders->count())
                {{ $orders->links() }}
            @endif
        </div>
    </div>
@endsection
