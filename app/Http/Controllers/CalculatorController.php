<?php

namespace App\Http\Controllers;

use App\Services\StructuredData\FAQSchema;
use App\Services\StructuredData\WebApplicationSchema;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalculatorController extends Controller
{
    /**
     * Display the dedicated student budget and visa proof calculator page.
     */
    public function index(Request $request): View
    {
        $locale = app()->getLocale();
        $isRtl = in_array($locale, ['fa'], true);

        // Optional city preselection via query parameter
        $availableCities = config('calculator.cities', []);
        $requestedCity = strtolower((string) $request->query('city', 'paris'));
        $initialCity = array_key_exists($requestedCity, $availableCities) ? $requestedCity : 'paris';

        $pageTitle = __('calculator.title');
        $pageDescription = __('calculator.subtitle') ?: 'Calculate your estimated student living budget, CAF housing subsidies, and official visa requirements for studying in France in 2026.';

        // Rich WebApplication JSON-LD schema
        $schema = new WebApplicationSchema(
            url: url()->current(),
            name: $pageTitle,
            description: $pageDescription,
            inLanguage: $locale
        );

        // Crawlable FAQs for SEO, Schema.org FAQPage, and AI Search Agents
        $faqs = $this->getCalculatorFaqs($locale);
        $faqSchema = (new FAQSchema)->addQuestions($faqs);

        return view('pages.calculator', compact(
            'pageTitle',
            'pageDescription',
            'schema',
            'faqSchema',
            'faqs',
            'locale',
            'isRtl',
            'initialCity',
            'availableCities'
        ));
    }

    /**
     * Get localized FAQs for the calculator page.
     */
    protected function getCalculatorFaqs(string $locale): array
    {
        return match ($locale) {
            'fa' => [
                [
                    'question' => 'حداقل تمکن مالی قانونی سفارت فرانسه برای ویزای دانشجویی در سال ۲۰۲۶ چقدر است؟',
                    'answer' => 'بر اساس بخش‌نامه‌های رسمی وزارت کشور و کنسولگری فرانسه، حداقل تمکن مالی قانونی (Minimum de ressources) معادل ۶۱۵ یورو در ماه برای یک سال تحصیلی (حداقل ۷,۳۸۰ یورو در سال) است. با این حال برای شهرهای گران‌تر مانند پاریس، ارائه حداقل ۹۰۰ الی ۱,۰۰۰ یورو در ماه به شدت برای کاهش ریسک ریجکتی پیشنهاد می‌شود.',
                ],
                [
                    'question' => 'آیا دانشجویان بین‌المللی و ایرانی می‌توانند از کمک‌هزینه مسکن CAF (APL) استفاده کنند؟',
                    'answer' => 'بله، تمامی دانشجویان بین‌المللی با ویزای دانشجویی معتبر (VLS-TS) بدون توجه به ملیت، از ماه دوم اقامت در فرانسه واجد شرایط دریافت سوبسید مسکن CAF بین ۱۵۰ تا ۲۱۰ یورو در ماه متناسب با نوع اقامتگاه و شهر هستند.',
                ],
                [
                    'question' => 'تفاوت نرخ شهریه مصوب Bienvenue en France با نرخ معاف (Exonération) چیست؟',
                    'answer' => 'شهریه استاندارد مصوب Bienvenue en France برای دانشجویان غیراروپایی سالانه ۲,۸۵۰ یورو (کارشناسی) و ۳,۸۷۹ یورو (ارشد) است؛ اما بسیاری از دانشگاه‌های دولتی فرانسه معافیت شهریه جزئی اعطا کرده و دانشجویان تنها شهریه دولتی عادی (~۲۵۰ یورو در سال) پرداخت می‌کنند.',
                ],
                [
                    'question' => 'هزینه ماهانه زندگی دانشجویی در پاریس در مقایسه با سایر شهرهای فرانسه چقدر تفاوت دارد؟',
                    'answer' => 'هزینه اجاره مسکن در پاریس بین ۴۰ تا ۶۰ درصد گران‌تر از شهرهایی مانند لیون، تولوز، استراسبورگ و گرونوبل است. در حالی که میانگین هزینه زندگی در استان‌ها ۶۵۰ تا ۸۵۰ یورو در ماه است، در پاریس این رقم معمولاً بین ۹۵۰ تا ۱,۲۵۰ یورو است.',
                ],
                [
                    'question' => 'آیا داشتن موجودی بانکی به تنهایی ضامن صدور ویزای تحصیلی است؟',
                    'answer' => 'خیر، تنها داشتن رقم تمکن کافی نیست. شفافیت منبع وجوه (Source of Funds)، ثبات گردش حساب بانکی ۶ ماهه، عدم واریزهای ناگهانی مشکوک، وضعیت مالی ساپورتر و انسجام برنامه تحصیلی نقش بسیار مهمی در تصمیم نهایی آفیسر کنسولگری ایفا می‌کنند.',
                ],
            ],
            'fr' => [
                [
                    'question' => 'Quel est le montant minimum légal de ressources pour un visa étudiant France 2026 ?',
                    'answer' => 'Le montant légal minimal exigé par le Ministère de l\'Intérieur français s\'élève à 615 € par mois, soit 7 380 € pour 12 mois. Pour des métropoles comme Paris, un montant de 850 € à 1 000 € par mois est fortement conseillé pour fiabiliser le dossier.',
                ],
                [
                    'question' => 'Les étudiants étrangers ont-ils droit aux aides au logement de la CAF (APL) ?',
                    'answer' => 'Oui, tout étudiant étranger titulaire d\'un visa de long séjour valant titre de séjour (VLS-TS) a légalement droit à l\'APL (Aide Personnalisée au Logement), déduisant de 150 € à 210 € par mois sur le loyer.',
                ],
                [
                    'question' => 'Quelle est la différence de coût de la vie entre Paris et la province ?',
                    'answer' => 'Le loyer étudiant à Paris est supérieur de 40 % à 60 % par rapport à Lyon, Toulouse, Strasbourg ou Grenoble. Le budget mensuel net est estimé à 950-1 250 € à Paris contre 650-850 € en province.',
                ],
            ],
            default => [
                [
                    'question' => 'What is the official minimum proof of funds for a France student visa in 2026?',
                    'answer' => 'According to French consular regulations, the official legal minimum is 615 € per month (7,380 € per academic year). For high-cost cities like Paris, demonstrating 850 € to 1,000 € monthly is strongly advised to minimize refusal risk.',
                ],
                [
                    'question' => 'Can international students apply for CAF (APL) housing assistance in France?',
                    'answer' => 'Yes, all international students holding a valid student visa (VLS-TS) are eligible for French CAF/APL housing allowances, granting between 150 € and 210 € per month in rent subsidies.',
                ],
                [
                    'question' => 'How does the student cost of living compare between Paris and regional cities?',
                    'answer' => 'Accommodation in Paris is 40% to 60% more expensive than cities like Lyon, Toulouse, Strasbourg, or Grenoble. Average net living costs range from 650-850 €/month in provincial hubs versus 950-1,250 €/month in Paris.',
                ],
            ],
        };
    }
}
