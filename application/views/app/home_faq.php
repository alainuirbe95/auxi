<?php
$faqs = [
    [
        'question' => 'How does the counter-offer system work?',
        'answer' => 'Hosts post jobs with their budget range, and cleaners submit offers with their proposed rates. If the cleaner\'s offer is higher than the host\'s budget, the host can negotiate or accept. This ensures both parties agree on fair pricing before work begins.'
    ],
    [
        'question' => 'What types of properties does AuxiApp serve?',
        'answer' => 'AuxiApp is perfect for short-term rentals (Airbnb, VRBO), vacation homes, rental properties, event preparation, post-renovation cleaning, and regular maintenance cleaning.'
    ],
    [
        'question' => 'How do cleaners get access to the property?',
        'answer' => 'When a job is accepted, the host receives a secure one-time password (OTP) that they share with the cleaner. The cleaner uses this code to access the property, ensuring security while eliminating the need for the host to be present during cleaning.'
    ],
    [
        'question' => 'What if I\'m not satisfied with the cleaning service?',
        'answer' => 'We have a comprehensive dispute system. If you\'re not satisfied, you can report issues within 24 hours of job completion. Our support team will review the situation and work to resolve any problems, including partial refunds if appropriate.'
    ],
    [
        'question' => 'How much can cleaners earn on AuxiApp?',
        'answer' => 'Earnings vary based on location, job complexity, and time invested. On average, cleaners earn $50+ per job. Part-time cleaners typically earn $400-800 per week, while full-time cleaners can earn $1,000-2,000 per week.'
    ],
    [
        'question' => 'What are the requirements to become a cleaner?',
        'answer' => 'Cleaners need a valid government-issued ID, a smartphone with GPS capability, basic cleaning supplies, and must pass our background check. No previous experience is required, but attention to detail and reliability are essential.'
    ],
    [
        'question' => 'How does payment work?',
        'answer' => 'Payments are processed securely through our platform. For hosts, payment is held in escrow until the job is completed satisfactorily. For cleaners, payment is released after job completion and any dispute period has passed (24 hours).'
    ],
    [
        'question' => 'Can I schedule recurring cleaning services?',
        'answer' => 'Yes! Hosts can set up recurring cleaning schedules for their properties. This is perfect for STR properties that need regular turnover cleaning or vacation homes that require periodic maintenance.'
    ],
    [
        'question' => 'What happens if a cleaner doesn\'t show up?',
        'answer' => 'If a cleaner doesn\'t show up or cancels last minute, we\'ll help you find a replacement quickly. The original cleaner may face penalties, and you\'ll get priority booking for future jobs.'
    ],
    [
        'question' => 'Is there insurance coverage for cleaning services?',
        'answer' => 'All cleaners are required to have appropriate insurance coverage. Additionally, our platform provides additional protection through our dispute resolution system and secure payment processing.'
    ],
    [
        'question' => 'How do I know if a cleaner is reliable?',
        'answer' => 'Every cleaner has a profile with ratings, reviews from previous clients, and completion statistics. You can also see their response time, acceptance rate, and any specializations they have.'
    ],
    [
        'question' => 'What cleaning supplies do I need to provide?',
        'answer' => 'Cleaners typically bring their own basic supplies, but you may need to provide specialized equipment or products for your specific property. This can be discussed during the offer negotiation process.'
    ],
    [
        'question' => 'Can I request specific cleaning tasks?',
        'answer' => 'Absolutely! When posting a job, you can specify exactly what needs to be cleaned, including any special requirements, areas to focus on, or tasks to avoid. This ensures cleaners know exactly what\'s expected.'
    ],
    [
        'question' => 'How far in advance should I book cleaning services?',
        'answer' => 'We recommend booking at least 24-48 hours in advance to ensure you get the best selection of cleaners. However, last-minute bookings are possible if cleaners are available in your area.'
    ],
    [
        'question' => 'What if there\'s damage to my property during cleaning?',
        'answer' => 'Report any damage immediately through our dispute system. We\'ll investigate the situation and work with both parties to resolve the issue, including arranging repairs or compensation as appropriate.'
    ],
    [
        'question' => 'Can I tip my cleaner?',
        'answer' => 'Tips are not required but are always appreciated for exceptional service. You can add a tip when reviewing the completed job, and it will be included in the cleaner\'s payment.'
    ],
    [
        'question' => 'How does the rating and review system work?',
        'answer' => 'After job completion, both hosts and cleaners can rate and review each other. This helps maintain quality standards and helps future users make informed decisions. Reviews are visible on profiles and help build reputation.'
    ],
    [
        'question' => 'What areas does AuxiApp currently serve?',
        'answer' => 'AuxiApp is expanding rapidly. We currently serve major metropolitan areas and are adding new locations regularly. Check our website or app to see if we\'re available in your area.'
    ],
    [
        'question' => 'Can I cancel a job after it\'s been accepted?',
        'answer' => 'Cancellation policies vary based on timing and circumstances. Cancelling well in advance typically has no penalty, while last-minute cancellations may incur fees. Check our cancellation policy for specific details.'
    ],
    [
        'question' => 'How do I become a verified cleaner?',
        'answer' => 'Complete the registration process, provide required documentation (ID, insurance), pass our background check, and complete a brief orientation. Once verified, you can start accepting jobs immediately.'
    ],
    [
        'question' => 'What\'s the difference between AuxiApp and other cleaning services?',
        'answer' => 'AuxiApp\'s counter-offer system ensures fair pricing for both parties. Our platform is specifically designed for property owners with multiple properties, offering features like recurring schedules, secure access, and comprehensive dispute resolution.'
    ],
    [
        'question' => 'Can I communicate directly with my cleaner?',
        'answer' => 'Yes, once a job is accepted, you can communicate directly through our secure messaging system. This allows you to discuss specific requirements, provide access instructions, or coordinate timing.'
    ],
    [
        'question' => 'What if I need cleaning services outside of normal hours?',
        'answer' => 'Many cleaners are available for evening, weekend, or early morning cleaning. When posting your job, specify your preferred time, and cleaners who are available during those hours will submit offers.'
    ],
    [
        'question' => 'How do I ensure my property is secure?',
        'answer' => 'All cleaners undergo background checks and are verified before joining our platform. The OTP system ensures only the assigned cleaner can access your property. Additionally, you can monitor job progress through our app.'
    ],
    [
        'question' => 'Can I request the same cleaner for future jobs?',
        'answer' => 'Absolutely! If you\'re happy with a cleaner\'s work, you can save them as a preferred cleaner and request them specifically for future jobs. This helps build long-term working relationships.'
    ],
    [
        'question' => 'What happens during peak seasons or high demand?',
        'answer' => 'During peak seasons, we recommend booking further in advance and being flexible with your schedule. Prices may fluctuate based on demand, but our counter-offer system ensures you still get competitive rates.'
    ]
];

foreach ($faqs as $index => $faq): ?>
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
