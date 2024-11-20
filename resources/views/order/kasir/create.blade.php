@extends('layouts.template')

@section('content')
    <div class="container mt-5">


        <!-- Form Container -->
        <form action="{{ route('kasir.order.store') }}" method="POST" class="card m-auto p-5 shadow-lg rounded-lg"
            style="background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
            @csrf
            {{-- Validasi error --}}
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger"
                    style="font-size: 16px; border-radius: 8px; background-color: #f8d7da; color: #721c24; padding: 12px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            {{-- @if (Session::get('failed'))
            <div class="alert alert-danger" style="font-size: 16px; background-color: #f8d7da; color: #721c24; border-radius: 8px; padding: 12px;">
                {{ Session::get('failed') }}
            </div>
        @endif --}}

            <!-- Penanggung Jawab -->
            <p class="mb-4" style="font-size: 18px; color: #333; font-weight: 600;">Penanggung Jawab:
                <b>{{ Auth::user()->name }}</b></p>

            <!-- Nama Pembeli -->
            <div class="mb-4 row">
                <label for="name_costumer" class="col-sm-3 col-form-label label-style">Nama Pembeli :</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="name_costumer"
                        value="{{ old('name_customer') }}" name="name_customer" required>
                </div>
            </div>

            <!-- Obat -->
            @if (old('medicines'))
                @foreach (old('medicines') as $no => $item)
                    <div class="mb-4 row" id="medicines-{{ $no }}">
                        <label for="medicines" class="col-sm-3-form-label">
                            Obat {{ $no + 1 }}
                            @if ($no > 0)
                                <span class="delete-button" onclick="deleteSelect('medicines-{{ $no }}')">X</span>
                            @endif
                        </label>
                        <div class="col-sm-9">
                            <select name="medicines[]" class="form-select" required style="font-size: 16px;">
                                <option selected hidden disabled>Pesanan 1</option>
                                @foreach ($medicines as $medItem)
                                    <option value="{{ $medItem['id'] }}">{{ $medItem['name'] }}</option>
                                @endforeach
                            </select>
                            <div id="medicines-wrap"></div>
                            <br>
                            <p class="text-primary" id="add-select"
                                style="cursor: pointer; font-weight: 500; font-size: 16px; color: #28a745; transition: color 0.3s ease;">
                                + Tambah Obat
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="mb-4 row" id="medicines-0">
                    <label for="medicines" class="col-sm-3 col-form-label label-style">Obat :</label>
                    <div class="col-sm-9">
                        <select name="medicines[]" class="form-select form-select-lg" required
                            style="font-size: 16px;">
                            <option selected hidden disabled>Pesanan 1</option>
                            @foreach ($medicines as $medItem)
                                <option value="{{ $medItem['id'] }}">{{ $medItem['name'] }}</option>
                            @endforeach
                        </select>
                        <div id="medicines-wrap" style="margin-top: 20px;"></div>
                        <br>
                        <p class="text-primary" id="add-select"
                            style="cursor: pointer; font-weight: 500; font-size: 16px; color: #28a745; transition: color 0.3s ease;">
                            + Tambah Obat
                        </p>
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                Konfirmasi Pembelian
            </button>
        </form>
    </div>
@endsection

@push('script')
    <script>
        let no = 1;
        $("#add-select").on("click", function() {
            // HTML with delete button (X) for each new select
            let html = `<div class="mb-4 row" id="medicines-${no}">
                        <label for="medicines" class="col-sm-3 col-form-label label-style">Obat ${no + 1}</label>
                        <div class="col-sm-9 d-flex align-items-center">
                            <select name="medicines[]" class="form-select form-select-lg" required style="font-size: 16px;">
                                <option selected hidden disabled>Pesanan ${no + 1}</option>
                                @foreach ($medicines as $medItem)
                                    <option value="{{ $medItem['id'] }}">{{ $medItem['name'] }}</option>
                                @endforeach
                            </select>
                            <span type="button" class="btn btn-danger btn-sm ms-2 delete-button" onclick="deleteSelect('medicines-${no}')">X</span>
                        </div>
                    </div>`;

            $("#medicines-wrap").append(html);
            no++;
        });

        // function deleteSelect(elementId){
        //     $("#" + elementId).remove();
        // }
        function deleteSelect(elementId) {
            let elementIdForDelete = "#" + elementId;
            $(elementIdForDelete).remove();
            no--;
        }
    </script>
@endpush
