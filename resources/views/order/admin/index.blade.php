@extends('layouts.template', ['title' => 'Pembelian'])

@section('content')
    <div class="container mt-3" style="margin-bottom: 20px">
        <div class="d-flex justify-content-end">
            <a href="{{ route('order.export-excel') }}" class="btn btn-primary">Export Data (excel)</a>
        </div>

        <form action="{{ route('order.riwayat') }}" method="GET" class="mb-4">
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
                    <th>Kasir</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($orders as $item)
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
