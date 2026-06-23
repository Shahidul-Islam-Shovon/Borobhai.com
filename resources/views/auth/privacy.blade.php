<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>Privacy Policy · Borobhai.online</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --bb-primary: #4f46e5;
            --bb-primary-dark: #4338ca;
            --bb-ink: #0f172a;
            --bb-muted: #64748b;
            --bb-line: #e2e8f0;
            --bb-bg: #f8fafc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bb-bg); color: var(--bb-ink); line-height: 1.7; }

        .legal-nav {
            background: #fff; border-bottom: 1px solid var(--bb-line);
            position: sticky; top: 0; z-index: 50; padding: 14px 0;
        }
        .legal-nav-inner {
            max-width: 860px; margin: 0 auto; padding: 0 24px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .legal-brand { display: flex; align-items: center; gap: 11px; text-decoration: none; }
        .legal-brand-icon {
            width: 40px; height: 40px; border-radius: 11px;
            background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
            box-shadow: 0 3px 10px rgba(79,70,229,.3);
        }
        .legal-brand-name { font-size: 1.2rem; font-weight: 800; color: var(--bb-ink); letter-spacing: -.5px; }
        .legal-back {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: .88rem; font-weight: 600; color: var(--bb-primary);
            text-decoration: none; padding: 8px 14px; border-radius: 10px; transition: background .2s ease;
        }
        .legal-back:hover { background: #eef2ff; }

        .legal-hero {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #7c73f0 100%);
            color: #fff; padding: 48px 24px 44px; position: relative; overflow: hidden;
        }
        .legal-hero::before {
            content: ''; position: absolute; width: 340px; height: 340px; border-radius: 50%;
            background: rgba(255,255,255,.1); filter: blur(50px); top: -120px; right: -60px;
        }
        .legal-hero-inner { max-width: 860px; margin: 0 auto; position: relative; z-index: 2; }
        .legal-hero .badge-doc {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.22);
            font-size: .8rem; font-weight: 600; padding: 6px 14px; border-radius: 20px; margin-bottom: 16px;
        }
        .legal-hero h1 { font-size: 2.1rem; font-weight: 800; letter-spacing: -.6px; margin-bottom: 8px; }
        .legal-hero p { font-size: .95rem; color: rgba(255,255,255,.82); }

        .legal-wrap { max-width: 860px; margin: -28px auto 60px; padding: 0 24px; position: relative; z-index: 3; }
        .legal-card {
            background: #fff; border-radius: 18px; padding: 40px 44px;
            box-shadow: 0 10px 40px rgba(15,23,42,.06), 0 1px 3px rgba(15,23,42,.04);
            border: 1px solid var(--bb-line);
        }
        .legal-updated {
            font-size: .82rem; color: var(--bb-muted); margin-bottom: 28px;
            padding-bottom: 20px; border-bottom: 1px solid var(--bb-line);
            display: flex; align-items: center; gap: 7px;
        }
        .legal-updated i { color: var(--bb-primary); }

        .legal-section { margin-bottom: 30px; }
        .legal-section:last-child { margin-bottom: 0; }
        .legal-section h2 {
            font-size: 1.18rem; font-weight: 700; color: var(--bb-ink); margin-bottom: 12px;
            letter-spacing: -.3px; display: flex; align-items: center; gap: 10px;
        }
        .legal-section h2 .num {
            width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
            background: #eef2ff; color: var(--bb-primary);
            display: inline-flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 700;
        }
        .legal-section p { font-size: .94rem; color: #374151; margin-bottom: 10px; }
        .legal-section ul { margin: 10px 0 10px 4px; padding: 0; list-style: none; }
        .legal-section li { font-size: .92rem; color: #374151; padding-left: 26px; position: relative; margin-bottom: 8px; }
        .legal-section li::before {
            content: '\f3ed'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
            position: absolute; left: 0; top: 1px; color: var(--bb-primary); font-size: .72rem;
        }
        .legal-section strong { color: var(--bb-ink); font-weight: 600; }

        .legal-note {
            background: #eef2ff; border-left: 3px solid var(--bb-primary);
            border-radius: 10px; padding: 14px 18px; margin: 14px 0; font-size: .9rem; color: #3730a3;
        }

        .legal-foot { max-width: 860px; margin: 0 auto; padding: 0 24px 50px; text-align: center; }
        .legal-foot-links { display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
        .legal-foot-links a {
            font-size: .9rem; font-weight: 600; color: var(--bb-primary); text-decoration: none;
            padding: 8px 16px; border-radius: 10px; background: #fff; border: 1px solid var(--bb-line); transition: all .2s ease;
        }
        .legal-foot-links a:hover { background: #eef2ff; border-color: #c7d2fe; }
        .legal-foot-copy { font-size: .82rem; color: var(--bb-muted); }

        @media (max-width: 600px) {
            .legal-card { padding: 28px 22px; }
            .legal-hero h1 { font-size: 1.6rem; }
            .legal-hero { padding: 36px 20px 38px; }
        }
    </style>
</head>
<body>

    <nav class="legal-nav">
        <div class="legal-nav-inner">
            <a href="{{ url('/') }}" class="legal-brand">
                <span class="legal-brand-icon"><i class="fa-solid fa-graduation-cap"></i></span>
                <span class="legal-brand-name">Borobhai.online</span>
            </a>
            <a href="{{ url()->previous() }}" class="legal-back">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </nav>

    <div class="legal-hero">
        <div class="legal-hero-inner">
            <div class="badge-doc"><i class="fa-solid fa-shield-halved"></i> Privacy</div>
            <h1>Privacy Policy</h1>
            <p>How we collect, use, and protect your personal information.</p>
        </div>
    </div>

    <div class="legal-wrap">
        <div class="legal-card">
            <div class="legal-updated">
                <i class="fa-regular fa-clock"></i> Last updated: June 2026
            </div>

            <div class="legal-section">
                <h2><span class="num">1</span> Introduction</h2>
                <p>At Borobhai.online, we respect your privacy and are committed to protecting your personal data. This Privacy Policy explains what information we collect, how we use it, and the choices you have.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">2</span> Information We Collect</h2>
                <p>We collect the following types of information:</p>
                <ul>
                    <li><strong>Account information:</strong> name, email address, role (Student/Alumni/Teacher), and password.</li>
                    <li><strong>Profile information:</strong> bio, department, session, skills, interests, profile and cover photos, and social links you choose to add.</li>
                    <li><strong>Content:</strong> posts, comments, documents, research papers, and job postings you create.</li>
                    <li><strong>Third-party login data:</strong> when you sign in with Google, we receive your basic profile (name, email) from Google.</li>
                    <li><strong>Usage data:</strong> basic technical information needed to operate the platform.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2><span class="num">3</span> How We Use Your Information</h2>
                <p>We use your information to:</p>
                <ul>
                    <li>Create and manage your account.</li>
                    <li>Display your profile and content to other members.</li>
                    <li>Enable networking, job postings, and applications.</li>
                    <li>Improve and maintain the platform.</li>
                    <li>Keep the community safe and enforce our Terms.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2><span class="num">4</span> Google Sign-In</h2>
                <p>When you choose to sign in with Google, we only access your <strong>name and email address</strong> to create or link your account. We do not access your contacts, files, or any other Google data. You can revoke this access anytime from your Google account settings.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">5</span> Information Sharing</h2>
                <p>We <strong>do not sell</strong> your personal information. Your profile and content are visible to other members of the Borobhai.online community as part of the platform's networking purpose. We do not share your data with external advertisers.</p>
                <div class="legal-note">
                    <i class="fa-solid fa-lock"></i> Your password is securely encrypted and never visible to anyone, including our team.
                </div>
            </div>

            <div class="legal-section">
                <h2><span class="num">6</span> Data Security</h2>
                <p>We take reasonable technical measures to protect your data, including encrypted passwords and secure connections. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">7</span> Your Rights &amp; Choices</h2>
                <p>You have the right to:</p>
                <ul>
                    <li>Access and update your profile information at any time.</li>
                    <li>Delete your posts, documents, and other content.</li>
                    <li>Delete your account, which removes your personal data from the platform.</li>
                    <li>Control what information you add to your public profile.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2><span class="num">8</span> Data Retention</h2>
                <p>We retain your information for as long as your account is active. When you delete your account, we remove your personal data, except where retention is required for legal or security reasons.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">9</span> Children's Privacy</h2>
                <p>Borobhai.online is intended for students, graduates, and teachers of recognized institutions. We do not knowingly collect data from children under the applicable age of digital consent.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">10</span> Changes &amp; Contact</h2>
                <p>We may update this Privacy Policy from time to time. Significant changes will be reflected on this page. If you have any questions about how we handle your data, please contact us through the platform's support channels.</p>
            </div>
        </div>
    </div>

    <div class="legal-foot">
        <div class="legal-foot-links">
            <a href="{{ route('terms') }}"><i class="fa-solid fa-file-contract"></i> Terms &amp; Conditions</a>
            <a href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i> Create Account</a>
            <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
        </div>
        <div class="legal-foot-copy">© {{ date('Y') }} Borobhai.online — All rights reserved.</div>
    </div>

</body>
</html>