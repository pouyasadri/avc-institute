@component('mail::message')
# {{ $dataRequest->locale === 'fa' ? 'تأیید درخواست حقوق داده' : ($dataRequest->locale === 'fr' ? 'Confirmation de votre demande' : 'Confirmation of Your Data Rights Request') }}

@if($dataRequest->locale === 'fa')
با سلام،

درخواست شما برای اعمال **{{ $dataRequest->request_type }}** دریافت شد.

مطابق با ماده ۱۲ مقررات GDPR، ما در عرض **۳۰ روز** به درخواست شما پاسخ خواهیم داد.

**شماره مرجع درخواست:** `{{ $dataRequest->id }}`
**تاریخ دریافت:** {{ $dataRequest->created_at->format('Y/m/d H:i') }}

برای هرگونه سوال با ما تماس بگیرید: [dpo@applyvipconseil.com](mailto:dpo@applyvipconseil.com)
@elseif($dataRequest->locale === 'fr')
Madame, Monsieur,

Nous avons bien reçu votre demande d'exercice du droit **{{ $dataRequest->request_type }}** conformément au RGPD.

Conformément à l'article 12 du RGPD, nous vous répondrons dans un délai de **30 jours** à compter de la date de réception.

**Référence de la demande :** `{{ $dataRequest->id }}`
**Reçue le :** {{ $dataRequest->created_at->format('d/m/Y à H:i') }}

Pour toute question : [dpo@applyvipconseil.com](mailto:dpo@applyvipconseil.com)
@else
Dear {{ $dataRequest->email }},

We have received your **{{ $dataRequest->request_type }}** request under the GDPR.

In accordance with Article 12 of the GDPR, we will respond within **30 days** of receipt.

**Request reference:** `{{ $dataRequest->id }}`
**Received on:** {{ $dataRequest->created_at->format('d M Y \a\t H:i') }}

For any questions, contact our DPO: [dpo@applyvipconseil.com](mailto:dpo@applyvipconseil.com)
@endif

@component('mail::panel')
{{ $dataRequest->locale === 'fa' ? 'این ایمیل به دلیل ارسال درخواست از طریق سایت applyvipconseil.com ارسال شده است.' : ($dataRequest->locale === 'fr' ? 'Cet e-mail vous a été envoyé car vous avez soumis une demande sur applyvipconseil.com.' : 'This email was sent because you submitted a data rights request at applyvipconseil.com.') }}
@endcomponent

**ApplyVIP Conseil (A.V.C Institute)**
67000 Strasbourg, France
[dpo@applyvipconseil.com](mailto:dpo@applyvipconseil.com)
@endcomponent
