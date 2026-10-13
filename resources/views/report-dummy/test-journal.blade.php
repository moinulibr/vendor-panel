<!DOCTYPE html>
<html>
<head>
  <title>Journal Entries - All Modules Combined</title>
  <style>
    body { font-family: Arial; padding: 20px; background: #f5f5f5; }
    h1 { color: #333; }
    table { width: 100%; border-collapse: collapse; background: white; margin-bottom: 20px; }
    th { background: #2c3e50; color: white; padding: 10px; text-align: left; }
    td { padding: 8px; border-bottom: 1px solid #ddd; }
    .debit { color: #27ae60; font-weight: bold; }
    .credit { color: #e74c3c; font-weight: bold; }
    .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; color: white; }
    .sale { background: #3498db; }
    .purchase { background: #9b59b6; }
    .expense { background: #e67e22; }
    .income { background: #27ae60; }
    .salary { background: #e74c3c; }
    .payment { background: #16a085; }
  </style>
</head>
<body>
  <h1>📘 Journal Entries — All Modules</h1>

  <table>
    <thead>
      <tr>
        <th>Entry#</th>
        <th>Date</th>
        <th>Module</th>
        <th>Reference</th>
        <th>Account</th>
        <th>Debit</th>
        <th>Credit</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td rowspan="2">JE-0001</td>
        <td rowspan="2">2025-01-10</td>
        <td rowspan="2"><span class="badge sale">Sale</span></td>
        <td rowspan="2">Sale #1 (INV-001)</td>
        <td>Cash A/C</td>
        <td class="debit">471.00</td>
        <td>—</td>
      </tr>
      <tr><td>Sales Revenue</td><td>—</td><td class="credit">471.00</td></tr>

      <tr>
        <td rowspan="2">JE-0002</td>
        <td rowspan="2">2025-01-15</td>
        <td rowspan="2"><span class="badge expense">Expense</span></td>
        <td rowspan="2">EXP-001</td>
        <td>Utilities Expense</td>
        <td class="debit">5,000.00</td>
        <td>—</td>
      </tr>
      <tr><td>Bank A/C</td><td>—</td><td class="credit">5,000.00</td></tr>

      <tr>
        <td rowspan="2">JE-0005</td>
        <td rowspan="2">2025-01-27</td>
        <td rowspan="2"><span class="badge purchase">Purchase</span></td>
        <td rowspan="2">PB-001 (ABC)</td>
        <td>Inventory</td>
        <td class="debit">69,359.85</td>
        <td>—</td>
      </tr>
      <tr>
        <td>VAT Payable + A/P</td>
        <td>—</td>
        <td class="credit">69,359.85</td>
      </tr>

      <tr>
        <td rowspan="2">JE-0007</td>
        <td rowspan="2">2025-01-31</td>
        <td rowspan="2"><span class="badge salary">Salary</span></td>
        <td rowspan="2">SAL-001 (Rahim)</td>
        <td>Salary Expense</td>
        <td class="debit">24,000.00</td>
        <td>—</td>
      </tr>
      <tr><td>Bank A/C</td><td>—</td><td class="credit">24,000.00</td></tr>

      <tr>
        <td rowspan="2">JE-0004</td>
        <td rowspan="2">2025-01-20</td>
        <td rowspan="2"><span class="badge income">Income</span></td>
        <td rowspan="2">INC-001</td>
        <td>Bank A/C</td>
        <td class="debit">1,200.00</td>
        <td>—</td>
      </tr>
      <tr><td>Other Income</td><td>—</td><td class="credit">1,200.00</td></tr>
    </tbody>
  </table>

  <h2>💡 এক লাইনে: সব module থেকে event → journal_entries → journal_lines</h2>
</body>
</html>