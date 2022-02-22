<div class="contract-draft__title-box">
    <div class="contract-draft__title">Subject Matter</div>
</div>
<div class="contract-draft__gray-box">
    <div>
        <div>
            <label class="contract-draft__input-label">Project Title</label>
            <p type="text" class="contract-draft__input">
                {{
                    $contract_draft['type'] === \App\Models\ContractDraft::TYPE_SINGLE_MILESTONE ?
                        $contract_draft['milestones'][0]['milestone_content']['title']  :  $contract_draft['title'] }}
            </p>
        </div>
        <div>
            <label class="contract-draft__input-label">
                Project Description
            </label>
            <p type="text" class="contract-draft__input">

                {{
                $contract_draft['type'] === \App\Models\ContractDraft::TYPE_SINGLE_MILESTONE ?
                    $contract_draft['milestones'][0]['milestone_content']['description'] :
                    $contract_draft['description']
                }}

            </p>
        </div>

        @if($contract_draft['type'] === \App\Models\ContractDraft::TYPE_SINGLE_MILESTONE)

            <table>
                <tr style="width: 100%;">
                    <td style="width: 50%; padding-right: 30px;">
                        <div>
                            <label class="contract-draft__input-label">Approx. Start Date</label>
                            <p type="text" class="contract-draft__input">
                               {{$contract_draft['milestones'][0]['milestone_content']['start_date']}}
                            </p>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div>
                            <label class="contract-draft__input-label">Approx. end date</label>
                            <p type="text" class="contract-draft__input">
                                {{$contract_draft['milestones'][0]['milestone_content']['end_date']}}

                            </p>
                        </div>
                    </td>
                </tr>

                <tr style="width: 100%;">
                    <td style="width: 50%; padding-right: 30px;">
                        <div>
                            <label class="contract-draft__input-label">Milestone Price, CAD</label>
                            <p type="text" class="contract-draft__input">
                                {{$contract_draft['milestones'][0]['milestone_content']['price']}}

                            </p>
                        </div>
                    </td>
                </tr>
            </table>

        @endif

    </div>
</div>