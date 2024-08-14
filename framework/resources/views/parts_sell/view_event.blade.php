<table class="table table-striped">
    <tr>
      <th>Item</th>
      <td>{{$row->item}}</td>
    </tr>
    <tr>
      <th>Unit</th>
      <td>{{$row->unit_details->name}}</td>
    </tr>
      <tr>
        <th>Category</th>
        <td>{{$row->manufacturer_details->name}}</td>
      </tr>
      <tr>
        <th>Stock</th>
        <td>{{$row->stock}}</td>
      </tr>
      <tr>
        <th>Min Stock</th>
        <td>{{$row->min_stock}}</td>
      </tr>
      <tr>
        <th>Description</th>
        <td>{{$row->description}}</td>
      </tr>
      <tr>
        <th>Created On</th>
        <td>{{Helper::getCanonicalDateTime($row->description,'default')}}</td>
      </tr>
</table>