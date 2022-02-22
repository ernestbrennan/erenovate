<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contract</title>

    @include('pdf.contract.style')

</head>
<body>
<div class="contract-comp__body">
    <div>
        <div class="contract-draft__title-box">
            <div class="contract-draft__title">Parties to the agreement</div>
        </div>
        <table>
            <tr style="width: 100%;">
                <td style="width: 50%;padding-left: 15px;padding-right: 15px;vertical-align: text-top;">
                    <div class="contract-draft__gray-box ">
                        <h3 class="contract-draft__box-title">Your Contact Information</h3>

                        @include('pdf.contract.parts.user_info', ['user_info' => $contract['current_user_info']])

                    </div>
                </td>
                <td style="width: 50%; padding-left: 15px;padding-right: 15px;vertical-align: text-top;">
                    <div class="contract-draft__gray-box">
                        <h3 class="contract-draft__box-title">Client Information</h3>

                        @include('pdf.contract.parts.user_info', ['user_info' => $contract['interlocutor_info']])

                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div>

        @include('pdf.contract.parts.subject_matter', ['contract_draft' => $contract_draft])

    </div>
    <div>
        @if($contract_draft['type'] === \App\Models\ContractDraft::TYPE_SEVERAL_MILESTONE)

            <div>

                @foreach($contract_draft['milestones'] as $milestone)

                    @include('pdf.contract.parts.milestone', ['milestone' => $milestone])

                @endforeach
            </div>

        @endif


        @include('pdf.contract.parts.summary', ['summary' => $contract_draft['summary']])


    </div>
</div>
</body>
</html>