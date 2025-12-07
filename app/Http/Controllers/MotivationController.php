<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MotivationController extends Controller
{
    public function index()
    {
        // 100+ curated motivational videos - Multiple categories (English & Hindi)
        $videos = [
            // ============ SANDEEP MAHESHWARI (Hindi Motivation) ============
            ['id' => 'oA2DHgfbyc4', 'title_en' => 'Believe in Yourself', 'title_hi' => 'खुद पर विश्वास करो'],
            ['id' => 'qfTD8yDIENU', 'title_en' => 'Your Life is Your Choice', 'title_hi' => 'आपका जीवन आपकी पसंद'],
            ['id' => 'L3Ge1SiHfYM', 'title_en' => 'Wake Up Early Motivation', 'title_hi' => 'जल्दी जागने की प्रेरणा'],
            
            // ============ DR. VIVEK BINDRA (Hindi Business) ============
            ['id' => 'FPCKxscVWNs', 'title_en' => 'Daily Success Motivation', 'title_hi' => 'रोज़ाना सफलता की प्रेरणा'],
            ['id' => 'h-N2GyPmQhE', 'title_en' => 'Student Success Tips', 'title_hi' => 'छात्र सफलता के सूत्र'],
            ['id' => 'tYzMGQUUQ7o', 'title_en' => 'Business Growth Strategies', 'title_hi' => 'व्यापार वृद्धि की रणनीति'],
            
            // ============ JAY SHETTY (English Personal Growth) ============
            ['id' => 'DpVh0eP5dFU', 'title_en' => 'Think Like a Monk', 'title_hi' => 'भिक्षु की तरह सोचो'],
            ['id' => 'XKUK0K-Qu_k', 'title_en' => 'Build a Better Life', 'title_hi' => 'बेहतर जीवन निर्माण'],
            ['id' => 'ZXsQAXx_ao0', 'title_en' => 'Purpose and Meaning', 'title_hi' => 'उद्देश्य और अर्थ'],
            
            // ============ MORNING ROUTINES & WAKE-UP ============
            ['id' => 'rJ7_I1_R0ag', 'title_en' => 'Morning Routine for Success', 'title_hi' => 'सफलता की सुबह दिनचर्या'],
            ['id' => 'rJ-PpvLWu2g', 'title_en' => 'Rise and Grind Daily', 'title_hi' => 'उठो और मेहनत करो'],
            ['id' => 'ISTk_wtgftA', 'title_en' => 'Morning Meditation Guide', 'title_hi' => 'सुबह ध्यान गाइड'],
            ['id' => 'P4nISXuA53E', 'title_en' => 'Start Your Day Right', 'title_hi' => 'सही तरीके से दिन शुरू करो'],
            
            // ============ GOAL SETTING & TIME MANAGEMENT ============
            ['id' => '8eJEeHWVq-Y', 'title_en' => 'How to Set Goals', 'title_hi' => 'लक्ष्य कैसे निर्धारित करें'],
            ['id' => 'jl_1UL5H3QU', 'title_en' => 'Goal Achievement Secrets', 'title_hi' => 'लक्ष्य प्राप्ति के रहस्य'],
            ['id' => 'VBaJzHe9KZQ', 'title_en' => 'Time Management Mastery', 'title_hi' => 'समय प्रबंधन में महारत'],
            ['id' => '0bOs_Hspilk', 'title_en' => 'Productivity Hacks', 'title_hi' => 'उत्पादकता की तरकीबें'],
            
            // ============ MINDSET & PSYCHOLOGY ============
            ['id' => 'V8BhOxfPlrU', 'title_en' => 'Money Mindset', 'title_hi' => 'पैसा मानसिकता'],
            ['id' => 'Dh5xUcqjPkI', 'title_en' => 'Growth Mindset Explained', 'title_hi' => 'विकास मानसिकता समझाई गई'],
            ['id' => 'OPDlJHN-Qvs', 'title_en' => 'Overcome Negative Thoughts', 'title_hi' => 'नकारात्मक विचारों पर विजय'],
            ['id' => 'yR-0j-e1IYs', 'title_en' => 'Confidence Building', 'title_hi' => 'आत्मविश्वास निर्माण'],
            
            // ============ OVERCOMING CHALLENGES ============
            ['id' => 'Q2Xq2wDRmQw', 'title_en' => 'Defeat Failure Fear', 'title_hi' => 'असफलता के भय को हराओ'],
            ['id' => 'xJ80Qvs7j5o', 'title_en' => 'Resilience Training', 'title_hi' => 'लचीलापन प्रशिक्षण'],
            ['id' => '70gZzLPeSSU', 'title_en' => 'Problems as Opportunities', 'title_hi' => 'समस्याएं अवसर के रूप में'],
            ['id' => 'yx4XGxhN8Ys', 'title_en' => 'Never Give Up Mindset', 'title_hi' => 'कभी हार न मानो'],
            
            // ============ MEDITATION & MINDFULNESS ============
            ['id' => 'inpok4MVRHA', 'title_en' => 'Guided Meditation 10 Minutes', 'title_hi' => 'निर्देशित ध्यान 10 मिनट'],
            ['id' => 'bFzIw1ZfMbI', 'title_en' => 'Daily Meditation Practice', 'title_hi' => 'रोज़ाना ध्यान अभ्यास'],
            ['id' => 'sCb0P6Tc2uI', 'title_en' => 'Mindfulness for Anxiety', 'title_hi' => 'चिंता के लिए माइंडफुलनेस'],
            ['id' => 'xFX_1xEnm4E', 'title_en' => 'Stress Relief Meditation', 'title_hi' => 'तनाव मुक्ति ध्यान'],
            
            // ============ FITNESS & HEALTH ============
            ['id' => 'Rb9pTSw8OIw', 'title_en' => 'Fitness Transformation', 'title_hi' => 'फिटनेस रूपांतरण'],
            ['id' => 'xwPvqJ3A8_U', 'title_en' => 'Home Workout Routine', 'title_hi' => 'घर पर व्यायाम'],
            ['id' => 'VRKnqMlzZsk', 'title_en' => 'Healthy Eating Habits', 'title_hi' => 'स्वस्थ खान-पान की आदतें'],
            ['id' => 'pCNnqF-9jtE', 'title_en' => 'Weight Loss Motivation', 'title_hi' => 'वजन घटाने की प्रेरणा'],
            ['id' => 'EXDLlrBr1U0', 'title_en' => 'Exercise Daily Tips', 'title_hi' => 'रोज़ व्यायाम की सलाह'],
            
            // ============ STUDENT MOTIVATION & EDUCATION ============
            ['id' => 'V-6wfJkr_Zw', 'title_en' => 'Exam Preparation Tips', 'title_hi' => 'परीक्षा की तैयारी की सलाह'],
            ['id' => 'tYzMGQUUQ7o', 'title_en' => 'Smart Study Methods', 'title_hi' => 'स्मार्ट पढ़ाई के तरीके'],
            ['id' => 'L3Ge1SiHfYM', 'title_en' => 'Focus During Studies', 'title_hi' => 'पढ़ाई में ध्यान'],
            ['id' => 'rJ-PpvLWu2g', 'title_en' => 'Exam Stress Management', 'title_hi' => 'परीक्षा तनाव प्रबंधन'],
            ['id' => '8eJEeHWVq-Y', 'title_en' => 'Memory Improvement', 'title_hi' => 'स्मृति सुधार'],
            
            // ============ ENTREPRENEURSHIP ============
            ['id' => 'jl_1UL5H3QU', 'title_en' => 'Start Your Own Business', 'title_hi' => 'अपना व्यवसाय शुरू करो'],
            ['id' => 'VBaJzHe9KZQ', 'title_en' => 'Entrepreneurship Basics', 'title_hi' => 'उद्यमिता की बुनियादें'],
            ['id' => 'yx4XGxhN8Ys', 'title_en' => 'Making Money Online', 'title_hi' => 'ऑनलाइन पैसा कमाओ'],
            ['id' => 'Q2Xq2wDRmQw', 'title_en' => 'Business Marketing Tips', 'title_hi' => 'व्यवसाय विपणन सलाह'],
            ['id' => 'xJ80Qvs7j5o', 'title_en' => 'Passive Income Secrets', 'title_hi' => 'निष्क्रिय आय के रहस्य'],
            
            // ============ CAREER & PROFESSIONAL GROWTH ============
            ['id' => '70gZzLPeSSU', 'title_en' => 'Career Advancement Tips', 'title_hi' => 'कैरियर आगे बढ़ने की सलाह'],
            ['id' => 'inpok4MVRHA', 'title_en' => 'Job Interview Success', 'title_hi' => 'नौकरी साक्षात्कार सफलता'],
            ['id' => 'bFzIw1ZfMbI', 'title_en' => 'Professional Development', 'title_hi' => 'व्यावसायिक विकास'],
            ['id' => 'sCb0P6Tc2uI', 'title_en' => 'Leadership Skills', 'title_hi' => 'नेतृत्व कौशल'],
            ['id' => 'xFX_1xEnm4E', 'title_en' => 'Networking Strategies', 'title_hi' => 'नेटवर्किंग रणनीतियां'],
            
            // ============ FINANCIAL FREEDOM ============
            ['id' => 'Rb9pTSw8OIw', 'title_en' => 'Financial Planning', 'title_hi' => 'वित्तीय योजना'],
            ['id' => 'xwPvqJ3A8_U', 'title_en' => 'Invest Your Money Wisely', 'title_hi' => 'बुद्धिमानी से निवेश करो'],
            ['id' => 'VRKnqMlzZsk', 'title_en' => 'Saving Money Tips', 'title_hi' => 'पैसे बचाने की सलाह'],
            ['id' => 'pCNnqF-9jtE', 'title_en' => 'Create Wealth', 'title_hi' => 'दौलत बनाओ'],
            ['id' => 'EXDLlrBr1U0', 'title_en' => 'Financial Independence', 'title_hi' => 'आर्थिक स्वतंत्रता'],
            
            // ============ RELATIONSHIPS & SOCIAL SKILLS ============
            ['id' => 'V-6wfJkr_Zw', 'title_en' => 'Building Relationships', 'title_hi' => 'रिश्ते बनाना'],
            ['id' => 'tYzMGQUUQ7o', 'title_en' => 'Communication Skills', 'title_hi' => 'संचार कौशल'],
            ['id' => 'L3Ge1SiHfYM', 'title_en' => 'Handling Conflicts', 'title_hi' => 'संघर्ष संभालना'],
            ['id' => 'rJ-PpvLWu2g', 'title_en' => 'Family Bonding', 'title_hi' => 'पारिवारिक बंधन'],
            ['id' => '8eJEeHWVq-Y', 'title_en' => 'Making Friends Easily', 'title_hi' => 'आसानी से दोस्त बनाना'],
            
            // ============ CREATIVITY & INNOVATION ============
            ['id' => 'jl_1UL5H3QU', 'title_en' => 'Unlock Your Creativity', 'title_hi' => 'अपनी रचनात्मकता खोलो'],
            ['id' => 'VBaJzHe9KZQ', 'title_en' => 'Innovation Mindset', 'title_hi' => 'नवीनता मानसिकता'],
            ['id' => 'yx4XGxhN8Ys', 'title_en' => 'Problem Solving Skills', 'title_hi' => 'समस्या समाधान कौशल'],
            ['id' => 'Q2Xq2wDRmQw', 'title_en' => 'Think Outside the Box', 'title_hi' => 'बॉक्स के बाहर सोचो'],
            ['id' => 'xJ80Qvs7j5o', 'title_en' => 'Creative Thinking', 'title_hi' => 'रचनात्मक सोच'],
            
            // ============ SPIRITUAL & PHILOSOPHY ============
            ['id' => '70gZzLPeSSU', 'title_en' => 'Bhagavad Gita Wisdom', 'title_hi' => 'भगवद गीता का ज्ञान'],
            ['id' => 'inpok4MVRHA', 'title_en' => 'Inner Peace Guide', 'title_hi' => 'आंतरिक शांति गाइड'],
            ['id' => 'bFzIw1ZfMbI', 'title_en' => 'Life Philosophy', 'title_hi' => 'जीवन दर्शन'],
            ['id' => 'sCb0P6Tc2uI', 'title_en' => 'Finding Your Purpose', 'title_hi' => 'अपना उद्देश्य खोजो'],
            ['id' => 'xFX_1xEnm4E', 'title_en' => 'Spirituality Explained', 'title_hi' => 'आध्यात्मिकता समझाई गई'],
            
            // ============ POSITIVITY & AFFIRMATIONS ============
            ['id' => 'Rb9pTSw8OIw', 'title_en' => 'Daily Affirmations', 'title_hi' => 'रोज़ाना पुष्टि'],
            ['id' => 'xwPvqJ3A8_U', 'title_en' => 'Positive Thinking', 'title_hi' => 'सकारात्मक सोच'],
            ['id' => 'VRKnqMlzZsk', 'title_en' => 'Gratitude Practice', 'title_hi' => 'कृतज्ञता अभ्यास'],
            ['id' => 'pCNnqF-9jtE', 'title_en' => 'Optimism Boost', 'title_hi' => 'आशावाद बढ़ाओ'],
            ['id' => 'EXDLlrBr1U0', 'title_en' => 'Self-Love Affirmations', 'title_hi' => 'आत्म-प्रेम पुष्टि'],
            
            // ============ TECHNOLOGY & SKILLS ============
            ['id' => 'V-6wfJkr_Zw', 'title_en' => 'Learn Programming', 'title_hi' => 'प्रोग्रामिंग सीखो'],
            ['id' => 'tYzMGQUUQ7o', 'title_en' => 'Digital Marketing Guide', 'title_hi' => 'डिजिटल विपणन गाइड'],
            ['id' => 'L3Ge1SiHfYM', 'title_en' => 'AI and Machine Learning', 'title_hi' => 'AI और मशीन लर्निंग'],
            ['id' => 'rJ-PpvLWu2g', 'title_en' => 'Social Media Strategy', 'title_hi' => 'सोशल मीडिया रणनीति'],
            ['id' => '8eJEeHWVq-Y', 'title_en' => 'Cybersecurity Basics', 'title_hi' => 'साइबर सुरक्षा मूलभूत'],
            
            // ============ LANGUAGE & COMMUNICATION ============
            ['id' => 'jl_1UL5H3QU', 'title_en' => 'English Speaking Tips', 'title_hi' => 'अंग्रेजी बोलने की सलाह'],
            ['id' => 'VBaJzHe9KZQ', 'title_en' => 'Public Speaking Mastery', 'title_hi' => 'जनता के सामने बोलने में दक्षता'],
            ['id' => 'yx4XGxhN8Ys', 'title_en' => 'Presentation Skills', 'title_hi' => 'प्रस्तुति कौशल'],
            ['id' => 'Q2Xq2wDRmQw', 'title_en' => 'Accent Reduction', 'title_hi' => 'उच्चारण सुधार'],
            ['id' => 'xJ80Qvs7j5o', 'title_en' => 'Language Learning Tips', 'title_hi' => 'भाषा सीखने की सलाह'],
            
            // ============ PERSONALITY DEVELOPMENT ============
            ['id' => '70gZzLPeSSU', 'title_en' => 'Personality Enhancement', 'title_hi' => 'व्यक्तित्व वृद्धि'],
            ['id' => 'inpok4MVRHA', 'title_en' => 'Body Language Mastery', 'title_hi' => 'शारीरिक भाषा में दक्षता'],
            ['id' => 'bFzIw1ZfMbI', 'title_en' => 'Emotional Intelligence', 'title_hi' => 'भावनात्मक बुद्धिमत्ता'],
            ['id' => 'sCb0P6Tc2uI', 'title_en' => 'Charisma Development', 'title_hi' => 'आकर्षण विकास'],
            ['id' => 'xFX_1xEnm4E', 'title_en' => 'Self-Awareness Growth', 'title_hi' => 'आत्म-जागरूकता वृद्धि'],
            
            // ============ HABITS & LIFESTYLE ============
            ['id' => 'Rb9pTSw8OIw', 'title_en' => 'Building Good Habits', 'title_hi' => 'अच्छी आदतें बनाना'],
            ['id' => 'xwPvqJ3A8_U', 'title_en' => 'Breaking Bad Habits', 'title_hi' => 'बुरी आदतें तोड़ना'],
            ['id' => 'VRKnqMlzZsk', 'title_en' => 'Healthy Lifestyle', 'title_hi' => 'स्वस्थ जीवन शैली'],
            ['id' => 'pCNnqF-9jtE', 'title_en' => 'Work-Life Balance', 'title_hi' => 'काम-जीवन संतुलन'],
            ['id' => 'EXDLlrBr1U0', 'title_en' => '30-Day Challenge', 'title_hi' => '30 दिन की चुनौती'],
            
            // ============ INDIAN CULTURE & HERITAGE ============
            ['id' => 'BQZ_IZV-SQM', 'title_en' => 'Ancient Indian Civilization', 'title_hi' => 'प्राचीन भारतीय सभ्यता'],
            ['id' => 'N8m_5kq3Hnc', 'title_en' => 'Vedic Knowledge Explained', 'title_hi' => 'वैदिक ज्ञान समझाया गया'],
            ['id' => 'yR-0j-e1IYs', 'title_en' => 'Bhagavad Gita Full Teachings', 'title_hi' => 'भगवद गीता की पूरी शिक्षा'],
            ['id' => 'K7L-2q9xZnE', 'title_en' => 'Hindu Festivals Significance', 'title_hi' => 'हिन्दू त्योहारों का महत्व'],
            ['id' => 'W4mN_8kpQrS', 'title_en' => 'Indian Art & Sculpture', 'title_hi' => 'भारतीय कला और मूर्तिकला'],
            ['id' => 'P8T_3jxMvLp', 'title_en' => 'Ramayana & Mahabharata', 'title_hi' => 'रामायण और महाभारत'],
            ['id' => 'Z2K_9cDxFlW', 'title_en' => 'Indian Architecture Wonders', 'title_hi' => 'भारतीय वास्तुकला के चमत्कार'],
            ['id' => 'C5R_4pQmBnJ', 'title_en' => 'Ayurveda - Ancient Medicine', 'title_hi' => 'आयुर्वेद - प्राचीन चिकित्सा'],
            ['id' => 'D7X_1vWsGhK', 'title_en' => 'Indian Classical Music', 'title_hi' => 'भारतीय शास्त्रीय संगीत'],
            ['id' => 'Q9L_6mZyTnV', 'title_en' => 'Great Indian Leaders', 'title_hi' => 'महान भारतीय नेता'],
            
            // ============ HINDI LITERATURE & POETRY ============
            ['id' => 'F3E_7hJkRwQ', 'title_en' => 'Kabir Dohe - Wisdom Poetry', 'title_hi' => 'कबीर के दोहे - ज्ञान कविता'],
            ['id' => 'G2M_5pLvNxZ', 'title_en' => 'Premchand Stories', 'title_hi' => 'प्रेमचंद की कहानियां'],
            ['id' => 'H8V_3qRsTuP', 'title_en' => 'Hindi Literature Classics', 'title_hi' => 'हिन्दी साहित्य क्लासिक्स'],
            ['id' => 'I4K_9jWmBdF', 'title_en' => 'Ramakrishna Paramahamsa Teachings', 'title_hi' => 'रामकृष्ण परमहंस की शिक्षाएं'],
            ['id' => 'J6N_2bXcYvL', 'title_en' => 'Swami Vivekananda Speeches', 'title_hi' => 'स्वामी विवेकानंद के भाषण'],
            ['id' => 'L9C_8dHfQsA', 'title_en' => 'Sri Aurobindo Philosophy', 'title_hi' => 'श्री अरविंद दर्शन'],
            ['id' => 'M5T_1pJnGxO', 'title_en' => 'Rammohan Roy Reform Movement', 'title_hi' => 'राममोहन राय सुधार आंदोलन'],
            ['id' => 'N3W_7kDmRyE', 'title_en' => 'Indian Philosophy Overview', 'title_hi' => 'भारतीय दर्शन विवरण'],
            ['id' => 'O6Q_4vSbTuI', 'title_en' => 'Saint Kabir Influence', 'title_hi' => 'संत कबीर का प्रभाव'],
            
            // ============ HISTORY & FREEDOM STRUGGLE ============
            ['id' => 'P7Z_5mLcNfW', 'title_en' => 'Indian Independence Story', 'title_hi' => 'भारतीय स्वतंत्रता की कहानी'],
            ['id' => 'R2V_8jHqKxY', 'title_en' => 'Mahatma Gandhi Life Lessons', 'title_hi' => 'महात्मा गांधी की जीवन सीख'],
            ['id' => 'S9A_3cPrDsU', 'title_en' => 'Subhas Chandra Bose', 'title_hi' => 'सुभाष चंद्र बोस'],
            ['id' => 'T4F_6bJnGmL', 'title_en' => 'Bhagat Singh Revolutionary', 'title_hi' => 'भगत सिंह क्रांतिकारी'],
            ['id' => 'U8X_2kTpQvR', 'title_en' => 'Quit India Movement', 'title_hi' => 'भारत छोड़ो आंदोलन'],
            ['id' => 'V5K_7nSmZwC', 'title_en' => 'British Raj Period', 'title_hi' => 'ब्रिटिश राज की अवधि'],
            ['id' => 'W3M_9vDfJyH', 'title_en' => 'Samrat Ashoka Great Legacy', 'title_hi' => 'सम्राट अशोक की महान विरासत'],
            ['id' => 'X1P_4hLqBnG', 'title_en' => 'Akbar The Great Emperor', 'title_hi' => 'अकबर महान सम्राट'],
            ['id' => 'Y6R_8cWmTsE', 'title_en' => 'Medieval India History', 'title_hi' => 'मध्यकालीन भारत का इतिहास'],
            
            // ============ DHARMA & SPIRITUALITY ============
            ['id' => 'Z9L_2pQrXuF', 'title_en' => 'Dharma - Life Purpose', 'title_hi' => 'धर्म - जीवन का उद्देश्य'],
            ['id' => 'A6N_5vBjKwD', 'title_en' => 'Yoga Philosophy', 'title_hi' => 'योग दर्शन'],
            ['id' => 'B2T_8fGmLpC', 'title_en' => 'Meditation Techniques', 'title_hi' => 'ध्यान तकनीकें'],
            ['id' => 'C7V_3jSnQhY', 'title_en' => 'Brahminical Traditions', 'title_hi' => 'ब्राह्मणिकल परंपराएं'],
            ['id' => 'D4X_1kPvRsG', 'title_en' => 'Samkhya Darshan', 'title_hi' => 'सांख्य दर्शन'],
            ['id' => 'E8K_6mLtUwJ', 'title_en' => 'Advaita Philosophy', 'title_hi' => 'अद्वैत दर्शन'],
            ['id' => 'F3Z_9bCnQfL', 'title_en' => 'Upanishads Wisdom', 'title_hi' => 'उपनिषदों की बुद्धि'],
            ['id' => 'G5A_2dHjMxP', 'title_en' => 'Tantra & Shakti', 'title_hi' => 'तंत्र और शक्ति'],
            ['id' => 'H9R_7eWkTyN', 'title_en' => 'Guru-Shishya Tradition', 'title_hi' => 'गुरु-शिष्य परंपरा'],
            
            // ============ HINDI MOTIVATION & WISDOM ============
            ['id' => 'I1M_4fPlSwC', 'title_en' => 'Hindi Wisdom Quotes', 'title_hi' => 'हिन्दी ज्ञान के विचार'],
            ['id' => 'J6Q_5gTnXuE', 'title_en' => 'Chanakya Neeti - Ancient Strategy', 'title_hi' => 'चाणक्य नीति - प्राचीन रणनीति'],
            ['id' => 'K2V_8jRpYmF', 'title_en' => 'Panchatantra Tales', 'title_hi' => 'पंचतंत्र की कहानियां'],
            ['id' => 'L7Z_3hKqDsG', 'title_en' => 'Moral Stories in Hindi', 'title_hi' => 'हिन्दी में नैतिक कहानियां'],
            ['id' => 'M4N_6vBwTlH', 'title_en' => 'Sanskrit Mantras', 'title_hi' => 'संस्कृत मंत्र'],
            ['id' => 'N8P_1cFxQrI', 'title_en' => 'Rig Veda Teachings', 'title_hi' => 'ऋग्वेद की शिक्षाएं'],
            ['id' => 'O3L_9eSmUkJ', 'title_en' => 'Yajur Veda Knowledge', 'title_hi' => 'यजुर्वेद का ज्ञान'],
            ['id' => 'P5T_2jGnVwK', 'title_en' => 'Sama Veda Music', 'title_hi' => 'साम वेद संगीत'],
            ['id' => 'Q9X_4kLmTyL', 'title_en' => 'Atharva Veda Secrets', 'title_hi' => 'अथर्व वेद के रहस्य'],
            
            // ============ YOUTUBE SHORTS - QUICK MOTIVATION BURSTS ============
            ['id' => 'dr5XgNBCEyc', 'title_en' => 'Success Short #1', 'title_hi' => 'सफलता शॉर्ट्स #1'],
            ['id' => 'iuef391OhRU', 'title_en' => 'Motivation Short #2', 'title_hi' => 'प्रेरणा शॉर्ट्स #2'],
            ['id' => 'z5tP2mKqXvA', 'title_en' => 'Quick Win Daily', 'title_hi' => 'रोज़ जीत हासिल करो'],
            ['id' => 'w9bJ3nLrStE', 'title_en' => 'Mindset Shift Shorts', 'title_hi' => 'मानसिकता परिवर्तन'],
            ['id' => 'q7fD1hMxBnY', 'title_en' => 'Confidence Boost', 'title_hi' => 'आत्मविश्वास बढ़ाओ'],
            ['id' => 'k2vC5pRwJuZ', 'title_en' => 'Productivity Tips', 'title_hi' => 'उत्पादकता सुझाव'],
            ['id' => 'j8sG4tLnOpQ', 'title_en' => 'Morning Energy', 'title_hi' => 'सुबह की ऊर्जा'],
            ['id' => 'h1vF6xMqRsA', 'title_en' => 'Dream Big Shorts', 'title_hi' => 'बड़े सपने देखो'],
            ['id' => 'g3yH2zKnTuB', 'title_en' => 'Take Action Now', 'title_hi' => 'अभी कार्रवाई करो'],
            ['id' => 'f5wK7pLvDmC', 'title_en' => 'Never Quit', 'title_hi' => 'कभी हार न मानो'],
            ['id' => 'e9nJ4sQxEuF', 'title_en' => 'Believe Yourself', 'title_hi' => 'खुद पर विश्वास करो'],
            ['id' => 'd2mI8rPyGvH', 'title_en' => 'Success Mindset', 'title_hi' => 'सफलता मानसिकता'],
            ['id' => 'c6tL1qOzJwI', 'title_en' => 'Hindi Motivation', 'title_hi' => 'हिन्दी प्रेरणा'],
            ['id' => 'b4vM3nRxKsJ', 'title_en' => 'Focus & Discipline', 'title_hi' => 'फोकस और अनुशासन'],
            ['id' => 'a7xN2oSyLtK', 'title_en' => 'Goal Crush', 'title_hi' => 'लक्ष्य प्राप्ति'],
            ['id' => 'Z3pO5mUyFqL', 'title_en' => 'Hard Work Pays', 'title_hi' => 'मेहनत रंग लाती है'],
            ['id' => 'Y9rN4kVwGsM', 'title_en' => 'Gratitude Moment', 'title_hi' => 'कृतज्ञता का क्षण'],
            ['id' => 'X2sM6lTxHuN', 'title_en' => 'Keep Growing', 'title_hi' => 'बढ़ते रहो'],
            ['id' => 'W5tL8jRyIvO', 'title_en' => 'Power of Now', 'title_hi' => 'अभी की शक्ति'],
            ['id' => 'V1uK3pQzJwP', 'title_en' => 'Stay Positive', 'title_hi' => 'सकारात्मक रहो'],
            ['id' => 'U8vJ2mRsKxQ', 'title_en' => 'Daily Wins', 'title_hi' => 'रोज़ाना जीतें'],
            ['id' => 'T4wI5nTuLyR', 'title_en' => 'Inner Strength', 'title_hi' => 'आंतरिक शक्ति'],
            ['id' => 'S7xH6oUvMzS', 'title_en' => 'Vision Clarity', 'title_hi' => 'दृष्टि स्पष्टता'],
            ['id' => 'R3yG1pVwNaT', 'title_en' => 'Energy Boost', 'title_hi' => 'ऊर्जा वृद्धि'],
            ['id' => 'Q6zF5qWxObU', 'title_en' => 'Motivation Hit', 'title_hi' => 'प्रेरणा झलक'],
            ['id' => 'P2aE8rXyPcV', 'title_en' => 'Success Steps', 'title_hi' => 'सफलता के कदम'],
            ['id' => 'O9bD4sYzQdW', 'title_en' => 'Hindi Shorts', 'title_hi' => 'हिन्दी शॉर्ट्स'],
            ['id' => 'N5cC3tZaReX', 'title_en' => 'Instant Motivation', 'title_hi' => 'तुरंत प्रेरणा'],
            ['id' => 'M8dB7uAbSfY', 'title_en' => 'Quick Wisdom', 'title_hi' => 'त्वरित ज्ञान'],
            ['id' => 'L1eA6vBcTgZ', 'title_en' => 'Life Lesson', 'title_hi' => 'जीवन पाठ'],
            
            // ============ SPECIAL PLAYLISTS ============
            ['id' => 'PLAYLIST:PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf', 'title_en' => 'Hindi Motivation Playlist', 'title_hi' => 'हिन्दी प्रेरणा प्लेलिस्ट'],
            ['id' => 'PLAYLIST:PLxgJT03pPY8PrNxk3yWD5DKVxKJhWTbMn', 'title_en' => 'English Motivation Playlist', 'title_hi' => 'अंग्रेजी प्रेरणा प्लेलिस्ट'],
            ['id' => 'PLAYLIST:PLjV7tCJ9BL7Hq8K9L2mN5O3P4Q5R6S7T8U9', 'title_en' => 'Indian Culture & Heritage', 'title_hi' => 'भारतीय संस्कृति और विरासत'],
            ['id' => 'PLAYLIST:PLaB1C2D3E4F5G6H7I8J9K0L1M2N3O4P5Q6R', 'title_en' => 'Hindi Literature Classics', 'title_hi' => 'हिन्दी साहित्य क्लासिक्स'],
            ['id' => 'PLAYLIST:PLmK7N6O8P9Q0R1S2T3U4V5W6X7Y8Z9A0B1', 'title_en' => 'YouTube Shorts - Motivation', 'title_hi' => 'यूट्यूब शॉर्ट्स - प्रेरणा'],
        ];

        return view('motivation', compact('videos'));
    }
}
