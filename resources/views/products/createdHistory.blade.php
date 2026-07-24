<div class="modal-dialog modal-md">
  <div class="modal-content">

      <div class="modal-header">
        <h1 class="modal-title">Product Created History</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
            <style>
              .summary-card {
                    background: #E6EAEF;
                    color: #010d14;
                    border-radius: 10px;
                    padding: 20px;
                    margin-bottom: 20px;
                    text-align: center;
                    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
                }
                .summary-card h3 {
                    margin: 0;
                    font-size: 2rem;
                    font-weight: 700;
                }
                .summary-card p {
                    margin: 0;
                    opacity: 0.9;
                    font-size: 0.9rem;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                /* টেবিলের কন্টেইনার স্টাইল */
                .history-container {
                    border: 1px solid #edf2f7;
                    border-radius: 12px;
                    background: #ffffff;
                }

                /* টেক্সট যেন ভেঙে নিচে না যায় (Horizontally Scrollable on Mobile) */
                .text-nowrap {
                    white-space: nowrap;
                }

                /* কাস্টম ব্যাজ স্টাইল */
                .count-badge {
                    display: inline-block;
                    background-color: #f0f7ff;
                    color: #007bff;
                    padding: 6px 16px;
                    border-radius: 50px;
                    font-weight: 600;
                    font-size: 0.9rem;
                    border: 1px solid #cce5ff;
                }

                /* স্ট্যাটাস ইন্ডিকেটর */
                .status-indicator {
                    font-size: 0.85rem;
                    color: #28a745;
                    font-weight: 500;
                }

                .status-indicator .dot {
                    height: 8px;
                    width: 8px;
                    background-color: #28a745;
                    border-radius: 50%;
                    display: inline-block;
                    margin-right: 5px;
                }

                /* মোবাইল ডিভাইসের জন্য স্পেশাল টাচ */
                @media (max-width: 576px) {
                    .custom-history-table th, 
                    .custom-history-table td {
                        font-size: 13px;
                        padding: 12px 10px !important;
                    }
                    .count-badge {
                        padding: 4px 10px;
                        font-size: 12px;
                    }
                }
            </style>

        <div class="summary-card">
            <p>Total Created Products</p>
            <h3>{{ number_format($totalCreatedProducts) }}</h3>
        </div>

          <div class="table-responsive history-container">
            <table class="table table-hover align-middle mb-0 custom-history-table">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap px-4 py-3">Created on (Date)</th>
                        <th class="text-center text-nowrap px-4">Total</th>
                        <th class="text-end text-nowrap px-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productHistory as $history)
                    <tr>
                        <td class="px-4 fw-medium text-dark">
                            {{ \Carbon\Carbon::parse($history->date)->format('d-m-Y') }}
                        </td>
                        <td class="text-center ">
                            <span class="count-badge shadow-sm status-indicator">{{ $history->total }}</span>
                        </td>
                        <td class="text-end px-4">
                            <span>
                              {{ \Carbon\Carbon::parse($history->date)->format('D, d M Y') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <p class="text-muted mb-0">No data found।</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        </div>
      </div>

      <div class="modal-footer d-flex gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
  </div>
</div>