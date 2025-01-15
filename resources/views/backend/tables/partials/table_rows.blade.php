@foreach ($tables as $table)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td class="text-center">{{ $table->table_number }}</td>
        <td class="text-center">
            <img src="{{ asset($table->qr_code) }}" alt="QR Code" style="max-width: 80px; height: 80px;">
        </td>
        <td class="text-center">
            <span class="badge {{ $table->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                {{ ucfirst($table->status) }}
            </span>
        </td>
        <td class="text-center">
            <div class="d-flex justify-content-center">
                <a href="{{ route('tables.edit', $table->id) }}" class="btn btn-primary btn-sm mr-2 mb-2 mb-md-0">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('tables.destroy', $table->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm mb-2 mb-md-0" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
