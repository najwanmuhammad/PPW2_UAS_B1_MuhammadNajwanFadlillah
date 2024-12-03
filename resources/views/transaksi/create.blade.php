<h2>Tambah Transaksi</h2>
<div class="card">
    <div class="card-header bg-white">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-danger">Kembali</a>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('transaksi.store') }}">
            @csrf
            <div class="d-flex flex-column gap-4 mb-4">
                <div class="form-group">
                    <label>Tanggal Pembelian</label>
                    <input type="date" class="form-control" name="tanggal_pembelian" value="{{ old('tanggal_pembelian') }}" required>
                </div>
            </div>

            <h6>Produk yang dibeli</h6>
            <div class="accordion mb-4" id="accordionItem">
                @for ($i = 0; $i < 3; $i++)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#item{{ $i }}" aria-expanded="false" aria-controls="item{{ $i }}">
                            Item #{{ $i + 1 }}
                        </button>
                    </h2>
                    <div id="item{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#accordionItem">
                        <div class="accordion-body">
                            <div class="d-flex flex-column gap-4 mb-4">
                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input type="text" class="form-control" name="nama_produk[]" value="{{ old('nama_produk.'.$i) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Harga Satuan</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" name="harga_satuan[]" value="{{ old('harga_satuan.'.$i) }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Qty</span>
                                        <input type="number" class="form-control" name="jumlah[]" value="{{ old('jumlah.'.$i) }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" name="subtotal[]" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <div class="form-group">
                <label>Harga Total</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" name="total_harga" value="{{ old('total_harga') }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <label>Bayar</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" name="bayar" value="{{ old('bayar') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label>Kembalian</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control" name="kembalian" value="{{ old('kembalian') }}" readonly>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

{{-- Custom JS --}}
<script>
    $(document).ready(function () {
        // Calculate subtotal for each product
        function calculateSubtotal(index) {
            const hargaSatuan = parseInt($(`input[name="harga_satuan[]"]`).eq(index).val()) || 0;
            const jumlah = parseInt($(`input[name="jumlah[]"]`).eq(index).val()) || 0;
            const subtotal = hargaSatuan * jumlah;

            $(`input[name="subtotal[]"]`).eq(index).val(subtotal);

            // Calculate total harga
            let totalHarga = 0;
            $('input[name="subtotal[]"]').each(function () {
                totalHarga += parseInt($(this).val()) || 0;
            });

            $('input[name="total_harga"]').val(totalHarga);
        }

        // Calculate kembalian
        function calculateKembalian() {
            const totalHarga = parseInt($('input[name="total_harga"]').val()) || 0;
            const bayar = parseInt($('input[name="bayar"]').val()) || 0;
            const kembalian = bayar - totalHarga;

            $('input[name="kembalian"]').val(kembalian);
        }

        // Bind events for calculating subtotal
        $('input[name="harga_satuan[]"], input[name="jumlah[]"]').on('input', function () {
            const index = $(this).closest('.accordion-item').index();
            calculateSubtotal(index);
        });

        // Bind event for calculating kembalian
        $('input[name="bayar"]').on('input', calculateKembalian);
    });
</script>
