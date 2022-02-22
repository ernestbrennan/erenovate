<div class="contract-draft__el-box">
    <div class="contract-draft__title-box">
        <div class="contract-draft__title">Items</div>
    </div>
    <div class="contract-draft__slide-main-box contract-draft__gray-box_invoice contract-draft__gray-box">

        @include('pdf.invoice.parts.works', ['works' => $invoice['works']] )

        @include('pdf.invoice.parts.materials', [
            'materials' => $invoice['materials'],
            'material_supply_side' =>$invoice['milestone']['milestone_content']['material_supply_side']
            ])

        <div>
            <div class="contract-draft__border-title">
                Related to milestone
            </div>
            <div class="contract-draft__input-box">
                <label
                       class="contract-draft__input-label">Milestone Title</label>
                <p>
                    {{$invoice['milestone']['milestone_content']['title']}}

                </p>
            </div>
            <div class="contract-draft__input-box">
                <label
                       class=" contract-draft__input-label">Milestone Description</label>
                <p>
                    {{$invoice['milestone']['milestone_content']['description']}}
                </p>
            </div>
            <div style="position: relative;" class="contract-draft__input-box">
            </div>
        </div>
    </div>
</div>

