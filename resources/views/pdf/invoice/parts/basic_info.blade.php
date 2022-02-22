<div class="contract-draft__el-box">
    <div class="contract-draft__title-box">
        <div class="contract-draft__title">Basic Information</div>
    </div>
    <div class="contract-draft__slide-main-box contract-draft__gray-box_invoice contract-draft__gray-box">
        <div class="contract-draft__input-box">
            <label class="contract-draft__input-label">Payment request title</label>
            <p>
                {{$invoice['title']}}
            </p>
        </div>

        <div  class="contract-draft__input-box">
            <label class="contract-draft__input-label">Payment request number</label>
            <p>
                {{$invoice['number']}}

            </p>
        </div>
        <div  class="contract-draft__input-box">
            <label class="contract-draft__input-label">Payment request description</label>
            <p>
                {{$invoice['description']}}

            </p>
        </div>
        <table style="width: 100%;">
            <tr>
                <td style="position: relative;width: 50%;" class="col-xl-6">
                    <div  class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Creation Date</label>
                        <div class="contract-draft__input_dropdown">
                            <p>
                                {{$invoice['creation_date']}}

                            </p>
                        </div>
                    </div>
                </td>
                <td style="position: relative;width: 50%;padding-left: 15px;" class="col-xl-6">
                    <div style="position: relative;" class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Due Date</label>
                        <div class="contract-draft__input_dropdown">
                            <p>
                                {{$invoice['due_date']}}
                            </p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

