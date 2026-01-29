@php
    $finalStatusLabel = [
        'oral_presentation' => 'Oral Presentation',
        'poster_presentation' => 'Displayed Poster Session',
    ];
@endphp

<div class="card shadow-sm rounded-0 rounded-bottom {{ $cardClass ?? '' }}">

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th style="width:60px">No.</th>
                    <th style="width:220px">Category</th>
                    <th style="width:220px">Presenter</th>
                    <th style="width:200px">Final Status</th>
                    <th>Title</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($papers as $index => $paper)
                    <tr>
                        <td>{{ $papers->firstItem() + $index }}</td>

                        <td>
                            {{ $paper->paperType->name }}
                        </td>

                        <td>
                            {{ optional($paper->authors->first())->name ?? '-' }}
                        </td>

                        <td>
                            {{ $finalStatusLabel[$paper->final_status] ?? '-' }}
                        </td>

                        <td>
                            {{ $paper->title }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="fw-semibold mb-1">No accepted papers available</div>
                            <div class="small">Please check again later</div>
                        </td>

                    </tr>
                @endforelse

            </tbody>
        </table>

    </div>

    @if ($papers instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card-footer bg-white">
            {{ $papers->links() }}
        </div>
    @endif
</div>
