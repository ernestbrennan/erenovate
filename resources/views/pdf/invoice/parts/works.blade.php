<h3 class="contract__gray-box-title">List of Services</h3>
<hr class="mb-5">
<table style="width: 100%;">
    <tr style="width: 100%;" class="list-works__table-titles">
        <td style="width: 45%;">
            <div class="table-label">
                Description
            </div>
        </td>
        <td style="width: 12%;padding-left: 15px;">
            <div class="table-label">
                Quantity
            </div>
        </td>
        <td style="width: 20%;padding-left: 15px;">
            <div class="table-label">
                Price per Unit
            </div>
        </td>
        <td style="width: 22%;padding-left: 15px;">
            <div class="table-label">
                Total Per Item
            </div>
        </td>
    </tr>

    @foreach($works as $work)

        <tr style="width: 100%;">
            <td style="width: 45%;">
                <p>
                    {{$work['title']}}
                </p>
            </td>
            <td style="width: 12%;padding-left: 15px;">
                <p>
                    {{$work['quantity']}}
                </p>
            </td>
            <td style="width: 20%;padding-left: 15px;">
                <p>
                    {{$work['price']}}
                </p>
            </td>
            <td style="width: 22%;padding-left: 15px;">
                <p>
                    {{ convert_price(format_price($work['price']) *  $work['quantity'])  }}
                </p>
            </td>
        </tr>

    @endforeach

</table>

