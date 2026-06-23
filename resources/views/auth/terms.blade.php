<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>Terms &amp; Conditions · Borobhai.online</title>

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

        /* ===== top nav ===== */
        .legal-nav {
            background: #fff; border-bottom: 1px solid var(--bb-line);
            position: sticky; top: 0; z-index: 50;
            padding: 14px 0;
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
            text-decoration: none; padding: 8px 14px; border-radius: 10px;
            transition: background .2s ease;
        }
        .legal-back:hover { background: #eef2ff; }

        /* ===== hero ===== */
        .legal-hero {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #7c73f0 100%);
            color: #fff; padding: 48px 24px 44px;
            position: relative; overflow: hidden;
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

        /* ===== content card ===== */
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
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .85rem; font-weight: 700;
        }
        .legal-section p { font-size: .94rem; color: #374151; margin-bottom: 10px; }
        .legal-section ul { margin: 10px 0 10px 4px; padding: 0; list-style: none; }
        .legal-section li {
            font-size: .92rem; color: #374151; padding-left: 26px; position: relative; margin-bottom: 8px;
        }
        .legal-section li::before {
            content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
            position: absolute; left: 0; top: 1px; color: var(--bb-primary); font-size: .72rem;
        }
        .legal-section strong { color: var(--bb-ink); font-weight: 600; }

        .legal-note {
            background: #eef2ff; border-left: 3px solid var(--bb-primary);
            border-radius: 10px; padding: 14px 18px; margin: 14px 0;
            font-size: .9rem; color: #3730a3;
        }

        /* ===== footer links ===== */
        .legal-foot {
            max-width: 860px; margin: 0 auto; padding: 0 24px 50px;
            text-align: center;
        }
        .legal-foot-links { display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
        .legal-foot-links a {
            font-size: .9rem; font-weight: 600; color: var(--bb-primary); text-decoration: none;
            padding: 8px 16px; border-radius: 10px; background: #fff; border: 1px solid var(--bb-line);
            transition: all .2s ease;
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

    {{-- Top Nav --}}
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

    {{-- Hero --}}
    <div class="legal-hero">
        <div class="legal-hero-inner">
            <div class="badge-doc"><i class="fa-solid fa-file-contract"></i> Legal</div>
            <h1>Terms &amp; Conditions</h1>
            <p>Please read these terms carefully before using Borobhai.online.</p>
        </div>
    </div>

    {{-- Content --}}
    <div class="legal-wrap">
        <div class="legal-card">
            <div class="legal-updated">
                <i class="fa-regular fa-clock"></i> Last updated: June 2026
            </div>

            <div class="legal-section">
                <h2><span class="num">1</span> Acceptance of Terms</h2>
                <p>By creating an account or using Borobhai.online (the "Platform"), you agree to be bound by these Terms &amp; Conditions. If you do not agree with any part of these terms, you may not use the Platform.</p>
                <p>Borobhai.online is an alumni networking and career platform that connects students, graduates (alumni), and teachers within an academic community.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">2</span> Eligibility &amp; Accounts</h2>
                <p>To use Borobhai.online, you must:</p>
                <ul>
                    <li>Be a current student, graduate, or teacher of a recognized institution.</li>
                    <li>Provide accurate and truthful information during registration.</li>
                    <li>Select the correct account type (Student, Alumni, or Teacher).</li>
                    <li>Be responsible for keeping your login credentials secure.</li>
                </ul>
                <p>You are responsible for all activity that occurs under your account. Notify us immediately of any unauthorized use.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">3</span> User Roles &amp; Permissions</h2>
                <p>Borobhai.online supports three account types, each with different capabilities:</p>
                <ul>
                    <li><strong>Students</strong> can connect with seniors, view and apply to job opportunities, and share posts.</li>
                    <li><strong>Alumni</strong> can post job opportunities, mentor juniors, and share content.</li>
                    <li><strong>Teachers</strong> can share academic resources and research, and view posts and jobs, but cannot post job opportunities.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2><span class="num">4</span> Acceptable Use</h2>
                <p>You agree not to use the Platform to:</p>
                <ul>
                    <li>Post false, misleading, harmful, or unlawful content.</li>
                    <li>Harass, abuse, or harm other members of the community.</li>
                    <li>Post fraudulent job listings or scam opportunities.</li>
                    <li>Impersonate any person or institution.</li>
                    <li>Upload viruses, malicious code, or attempt to breach our security.</li>
                </ul>
                <div class="legal-note">
                    <i class="fa-solid fa-circle-info"></i> Violation of these rules may result in temporary suspension or permanent removal of your account.
                </div>
            </div>

            <div class="legal-section">
                <h2><span class="num">5</span> Content Ownership</h2>
                <p>You retain ownership of the content you post (posts, documents, research, comments). However, by posting on Borobhai.online, you grant us a non-exclusive license to display and distribute that content within the Platform.</p>
                <p>You are solely responsible for the content you share, including theses, research papers, and job postings.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">6</span> Job Postings &amp; Applications</h2>
                <p>Alumni may post job opportunities. Borobhai.online does not guarantee the accuracy or legitimacy of any job posting and is not responsible for any outcome of job applications. Users should exercise their own judgment.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">7</span> Third-Party Login</h2>
                <p>You may sign in using third-party services such as Google. By doing so, you authorize us to access basic profile information (name, email) from that service, as described in our Privacy Policy.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">8</span> Termination</h2>
                <p>We reserve the right to suspend or terminate your account at any time if you violate these Terms, without prior notice. You may also delete your account at any time.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">9</span> Changes to Terms</h2>
                <p>We may update these Terms from time to time. Continued use of the Platform after changes means you accept the revised Terms. We encourage you to review this page periodically.</p>
            </div>

            <div class="legal-section">
                <h2><span class="num">10</span> Contact</h2>
                <p>If you have questions about these Terms, please contact us through the Platform's support channels.</p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="legal-foot">
        <div class="legal-foot-links">
            <a href="{{ route('privacy') }}"><i class="fa-solid fa-shield-halved"></i> Privacy Policy</a>
            <a href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i> Create Account</a>
            <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
        </div>
        <div class="legal-foot-copy">© {{ date('Y') }} Borobhai.online — All rights reserved.</div>
    </div>

</body>
</html>