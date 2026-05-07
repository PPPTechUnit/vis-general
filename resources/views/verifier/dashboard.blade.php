@extends('layouts.verifier_layout')
@section('title', 'Dashboard')

@section('content')



    <div class="container-fluid py-4">

        {{-- Blockcode --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">Blockcode</h6>
                        <div class="row">
                            <div class="col-3 col-md-3">
                                <div class="card border-0 bg-success-subtle">
                                    <div class="card-body">
                                        <div class="small fw-semibold mb-1">Total blockcodes</div>
                                        <div class="fs-4 fw-semibold stat-total">{{ number_format($stats['total']) }}</div>
                                    </div>
                                </div>
                            </div>
                            @foreach([
                                ['label'=>'Condition Good',    'key'=>'GOOD',    'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Condition Average', 'key'=>'AVERAGE', 'bg'=>'warning-subtle',   'text'=>'warning'],
                                ['label'=>'Condition Bad',     'key'=>'BAD',     'bg'=>'danger-subtle',    'text'=>'danger'],
                               // ['label'=>'Not set', 'key'=>'null',    'bg'=>'secondary-subtle', 'text'=>'secondary'],
                            ] as $item)
                                <div class="col-6 col-md-3">
                                    <div class="card border-0 bg-{{ $item['bg'] }}">
                                        <div class="card-body">
                                            <div class="small text-{{ $item['text'] }} fw-semibold mb-1">{{ $item['label'] }}</div>
                                            <div class="fs-4 fw-semibold stat-cond-{{ $item['key'] }}">{{ number_format($stats['cond'][$item['key']]) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->group == 'main'  )


        {{-- Scanned --}}
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">Scanned</h6>
                        <div class="row g-3 mb-3">
                            @foreach([
                                ['label'=>'Done',    'key'=>'DONE',    'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Pending', 'key'=>'PENDING', 'bg'=>'secondary-subtle', 'text'=>'secondary'],
                                ['label'=>'Issue',   'key'=>'ISSUE',   'bg'=>'danger-subtle',    'text'=>'danger'],
                            ] as $item)
                                <div class="col-6 col-md-4">
                                    <div class="card border-0 bg-{{ $item['bg'] }}">
                                        <div class="card-body">
                                            <div class="small text-{{ $item['text'] }} fw-semibold mb-1">{{ $item['label'] }}</div>
                                            <div class="fs-4 fw-semibold stat-scanned-{{ $item['key'] }}">{{ number_format($stats['scanned'][$item['key']]) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">Deskewed</h6>
                        <div class="row g-3 mb-3">
                            @foreach([
                                ['label'=>'Done',    'key'=>'DONE',    'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Pending', 'key'=>'PENDING', 'bg'=>'secondary-subtle', 'text'=>'secondary'],
                                ['label'=>'Issue',   'key'=>'ISSUE',   'bg'=>'danger-subtle',    'text'=>'danger'],
                            ] as $item)
                                <div class="col-6 col-md-4">
                                    <div class="card border-0 bg-{{ $item['bg'] }}">
                                        <div class="card-body">
                                            <div class="small text-{{ $item['text'] }} fw-semibold mb-1">{{ $item['label'] }}</div>
                                            <div class="fs-4 fw-semibold stat-deskewed-{{ $item['key'] }}">{{ number_format($stats['deskewed'][$item['key']]) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>


        {{-- Converted --}}
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">Converted</h6>
                        <div class="row g-3 mb-3">
                            @foreach([
                                ['label'=>'Done',    'key'=>'DONE',    'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Pending', 'key'=>'PENDING', 'bg'=>'secondary-subtle', 'text'=>'secondary'],
                                ['label'=>'Issue',   'key'=>'ISSUE',   'bg'=>'danger-subtle',    'text'=>'danger'],
                            ] as $item)
                                <div class="col-6 col-md-4">
                                    <div class="card border-0 bg-{{ $item['bg'] }}">
                                        <div class="card-body">
                                            <div class="small text-{{ $item['text'] }} fw-semibold mb-1">{{ $item['label'] }}</div>
                                            <div class="fs-4 fw-semibold stat-converted-{{ $item['key'] }}">{{ number_format($stats['converted'][$item['key']]) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">QA Converted</h6>
                        <div class="row g-3 mb-3">
                            @foreach([
                                ['label'=>'Done',        'key'=>'DONE',        'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Pending',     'key'=>'PENDING',     'bg'=>'secondary-subtle', 'text'=>'secondary'],
                                ['label'=>'Discrepancy', 'key'=>'DISCREPANCY', 'bg'=>'warning-subtle',   'text'=>'warning'],
                            ] as $item)
                                <div class="col-6 col-md-4">
                                    <div class="card border-0 bg-{{ $item['bg'] }}">
                                        <div class="card-body">
                                            <div class="small text-{{ $item['text'] }} fw-semibold mb-1">{{ $item['label'] }}</div>
                                            <div class="fs-4 fw-semibold stat-qa-{{ $item['key'] }}">{{ number_format($stats['qa'][$item['key']]) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>



        {{-- Voter Totals --}}
       {{-- <div class="row">
            <div class="col-12">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">Voters overview</h6>
                        <div class="row g-3">
                            @foreach([
                                ['Male voters',   'male'],
                                ['Female voters', 'female'],
                                ['Total voters',  'total'],
                                ['Count issues',  'issues'],
                            ] as [$label, $key])
                                <div class="col-6 col-md-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <div class="small text-muted mb-1">{{ $label }}</div>
                                            <div class="fs-5 fw-semibold stat-voters-{{ $key }}">{{ number_format($stats['voters'][$key]) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}

        {{-- Last updated indicator --}}
        <div class="text-end text-muted small mt-3 pe-2">
            Last updated: <span id="last-updated">just now</span>
        </div>

            @endif

    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const INTERVAL_MS = 60_000; // 1 minute

        // Map CSS selector → function to extract value from the stats JSON
        const statMap = {
                    '.stat-total': d => d.total,

            '.stat-cond-GOOD':    d => d.cond.GOOD,
            '.stat-cond-AVERAGE': d => d.cond.AVERAGE,
            '.stat-cond-BAD':     d => d.cond.BAD,
            '.stat-cond-null':    d => d.cond['null'],

            '.stat-scanned-DONE':    d => d.scanned.DONE,
            '.stat-scanned-PENDING': d => d.scanned.PENDING,
            '.stat-scanned-ISSUE':   d => d.scanned.ISSUE,

            '.stat-deskewed-DONE':    d => d.deskewed.DONE,
            '.stat-deskewed-PENDING': d => d.deskewed.PENDING,
            '.stat-deskewed-ISSUE':   d => d.deskewed.ISSUE,

            '.stat-converted-DONE':    d => d.converted.DONE,
            '.stat-converted-PENDING': d => d.converted.PENDING,
            '.stat-converted-ISSUE':   d => d.converted.ISSUE,

            '.stat-qa-DONE':        d => d.qa.DONE,
            '.stat-qa-PENDING':     d => d.qa.PENDING,
            '.stat-qa-DISCREPANCY': d => d.qa.DISCREPANCY,

            '.stat-voters-male':   d => d.voters.male,
            '.stat-voters-female': d => d.voters.female,
            '.stat-voters-total':  d => d.voters.total,
            '.stat-voters-issues': d => d.voters.issues,
    };

        function fmt(n) {
            return Number(n).toLocaleString();
        }

        function applyStats(data) {
            Object.entries(statMap).forEach(([selector, getter]) => {
                const el = document.querySelector(selector);
            if (!el) return;

            const newVal = fmt(getter(data));
            if (el.textContent !== newVal) {
                // Brief flash to signal an update
                el.classList.add('text-primary');
                el.textContent = newVal;
                setTimeout(() => el.classList.remove('text-primary'), 1000);
            }
        });

            // Update "last updated" timestamp
            const ts = document.getElementById('last-updated');
            if (ts) {
                const now = new Date();
                ts.textContent = now.toLocaleTimeString();
            }
        }

        function fetchStats() {
            fetch('{{ route("dashboard.stats") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
                .then(r => {
                if (!r.ok) throw new Error('Network response was not ok');
            return r.json();
        })
        .then(applyStats)
                .catch(err => console.warn('Stats refresh failed:', err));
        }

        // Start polling
        setInterval(fetchStats, INTERVAL_MS);
    })();
</script>
@endpush