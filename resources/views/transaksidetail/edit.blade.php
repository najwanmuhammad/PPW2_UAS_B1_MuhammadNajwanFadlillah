<h2>Edit Detail Transaksi</h2>
<div class="card">
    <div class="card-header bg-white">
        <a href="{{ route('transaksidetail.detail', $transaksidetail->id_transaksi) }}" class="btn btn-outline-danger">Kembali</a>
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
        <form method="POST" action="{{ route('transaksidetail.update', $transaksidetail->id) }}">
            @csrf
            @method('PUT')
            <div class="d-flex flex-column gap-4 mb-4">
                <!-- Nama Produk -->
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" class="form-control" name="nama_produk" value="{{ $transaksidetail->nama_produk }}" required>
                </div>

                <!-- Harga Satuan -->
                <div class="form-group">
                    <label>Harga Satuan</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" name="harga_satuan" value="{{ $transaksidetail->harga_satuan }}" required>
                    </div>
                </div>

                <!-- Jumlah -->
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" class="form-control" name="jumlah" value="{{ $transaksidetail->jumlah }}" required>
                </div>

                <!-- Subtotal -->
                <div class="form-group">
                    <label>Subtotal</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control" name="subtotal" value="{{ $transaksidetail->subtotal }}" readonly>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

{{-- Custom JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hargaSatuanInput = document.querySelector('input[name="harga_satuan"]');
        const jumlahInput = document.querySelector('input[name="jumlah"]');
        const subtotalInput = document.querySelector('input[name="subtotal"]');

        function calculateSubtotal() {
            const hargaSatuan = parseInt(hargaSatuanInput.value) || 0;
            const jumlah = parseInt(jumlahInput.value) || 0;
            const subtotal = hargaSatuan * jumlah;
            subtotalInput.value = subtotal.toLocaleString('id-ID');
        }

        hargaSatuanInput.addEventListener('input', calculateSubtotal);
        jumlahInput.addEventListener('input', calculateSubtotal);
    });
</script>
