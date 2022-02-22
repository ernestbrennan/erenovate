@if($user_info['type'] === \App\Models\UserInfo::TYPE_INDIVIDUAL)
    <div>
        <label class="contract-draft__input-label">First Name</label>
        <p type="text" class="contract-draft__input">
            {{$user_info['first_name']}}
        </p>
    </div>
    <div>
        <label class="contract-draft__input-label">Last Name</label>
        <p type="text" class="contract-draft__input">
            {{$user_info['last_name']}}
        </p>
    </div>
@endif

@if($user_info['type']  === \App\Models\UserInfo::TYPE_LEGAL)
    <div>
        <label class="contract-draft__input-label">Company Name</label>
        <p type="text" class="contract-draft__input">
            {{$user_info['company_name']}}
        </p>
    </div>
    <div>
        <label class="contract-draft__input-label">Name of Representative</label>
        <p type="text" class="contract-draft__input">
            {{$user_info['representative_name']}}
        </p>
    </div>
@endif


<div class="contract-draft__sep-title">Address</div>
<div>
    <label class="contract-draft__input-label">Address Line 1</label>
    <p type="text" class="contract-draft__input">
        {{$user_info['address_1']}}
    </p>
</div>
<div>
    <label class="contract-draft__input-label">Address Line 2</label>
    <p type="text" class="contract-draft__input">
        {{$user_info['address_2']}}
    </p>
</div>
<div>
    <label class="contract-draft__input-label">City</label>
    <p type="text" class="contract-draft__input">
        {{$user_info['city']}}
    </p>
</div>
<div>
    <label class="contract-draft__input-label">Province</label>
    <p type="text" class="contract-draft__input">
        {{$user_info['province']}}
    </p>
</div>
<div>
    <label class="contract-draft__input-label">Postal Code</label>
    <p type="text" class="contract-draft__input">
        {{$user_info['postal_code']}}
    </p>
</div>