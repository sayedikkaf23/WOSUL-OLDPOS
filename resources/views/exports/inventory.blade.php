 <table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Branch</th>
        <th>Opening Quantity</th>
        <th>Purchase Quantity</th>
        <th>Transfer From Quantity</th>
        <th>Transfer To Quantity</th>
        <th>Sales Quantity</th>
        <th>Return Quantity</th>
        <th>Damage Quantity</th>
        <th>Adjustment Quantity</th>
        <th>Stock Return Quantity</th>
        <th>Variance Quantity</th>
        <th>Available Quantity</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $inventory)
        <tr>
            <td>{{ $inventory['product_name'] }}</td>
            <td>{{ $inventory['branch'] }}</td>
            <td>{{ $inventory['opening_quantity'] }}</td>
            <td>{{ $inventory['purchase_quantity'] }}</td>
            <td>{{ $inventory['transfer_from_quantity'] }}</td>
            <td>{{ $inventory['transfer_to_quantity'] }}</td>
            <td>{{ $inventory['sold_quantity'] }}</td>
            <td>{{ $inventory['returned_quantity'] }}</td>
            <td>{{ $inventory['damaged_quantity'] }}</td>
            <td>{{ $inventory['adjustment_quantity'] }}</td>
            <td>{{ $inventory['stock_returned_quantity'] }}</td>
            <td>{{ $inventory['variance_quantity'] }}</td>
            <td>{{ $inventory['available_quantity'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table> 