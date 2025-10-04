<?php
$preview_faqs = [
    [
        'question' => 'How does the counter-offer system work?',
        'answer' => 'Hosts post jobs with their budget range, and cleaners submit offers with their proposed rates. If the cleaner\'s offer is higher than the host\'s budget, the host can negotiate or accept. This ensures both parties agree on fair pricing before work begins.'
    ],
    [
        'question' => 'What types of properties does AuxiApp serve?',
        'answer' => 'AuxiApp is perfect for short-term rentals (Airbnb, VRBO), vacation homes, rental properties, event preparation, post-renovation cleaning, and regular maintenance cleaning.'
    ],
    [
        'question' => 'How much can cleaners earn on AuxiApp?',
        'answer' => 'Earnings vary based on location, job complexity, and time invested. On average, cleaners earn $50+ per job. Part-time cleaners typically earn $400-800 per week, while full-time cleaners can earn $1,000-2,000 per week.'
    ],
    [
        'question' => 'What if I\'m not satisfied with the cleaning service?',
        'answer' => 'We have a comprehensive dispute system. If you\'re not satisfied, you can report issues within 24 hours of job completion. Our support team will review the situation and work to resolve any problems, including partial refunds if appropriate.'
    ],
    [
        'question' => 'How does payment work?',
        'answer' => 'Payments are processed securely through our platform. For hosts, payment is held in escrow until the job is completed satisfactorily. For cleaners, payment is released after job completion and any dispute period has passed (24 hours).'
    ]
];

foreach ($preview_faqs as $index => $faq): ?>
    <div class="faq-item">
        <button class="faq-question">
            <span><?php echo htmlspecialchars($faq['question']); ?></span>
            <i class="fas fa-chevron-down faq-icon"></i>
        </button>
        <div class="faq-answer">
            <p><?php echo htmlspecialchars($faq['answer']); ?></p>
        </div>
    </div>
<?php endforeach; ?>

<!-- View All FAQ Link -->
<div style="text-align: center; margin-top: 3rem;">
    <a href="<?php echo base_url('app/faq'); ?>" class="btn btn-outline" style="padding: 16px 32px; font-size: 1.1rem;">
        <i class="fas fa-question-circle"></i>
        View All FAQs
    </a>
</div>
