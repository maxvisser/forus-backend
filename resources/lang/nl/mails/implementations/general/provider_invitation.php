<?php

return [
    'title' => 'U bent uitgenodigd voor :fund_name',
    'dear_provider' => 'Beste ":provider_name",',

    'invitation_text' => join("\n\n", [
        "U krijgt deze e-mail omdat u aangemeld staat voor \":from_fund_name\". Vanaf :fund_start_date zal \":sponsor_name\" een nieuw fonds uitgeven: \":fund_name\".",
        "De periode loopt van :fund_start_date tot :fund_end_date. Ook tijdens deze periode kunt u een aanbod (opnieuw) plaatsen.",
        "We hopen dat u meedoet.",
        "Heeft u vragen of loopt u tegen problemen aan bij de aanmelding? Dan kunt u een mail sturen naar :sponsor_email of bel naar telefoonnummer :sponsor_phone.",
    ]),

    'button_text' => 'AANMELDEN',
    'accept_invitation' => 'Klik <a href=":link" target="_blank" style="color: #315efd; text-decoration: underline;">hier</a> of op de knop hieronder om u aan te melden.
'
];
