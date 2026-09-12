# Laravel Benchmark Test Suite

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://www.php.net/)
[![Framework](https://img.shields.io/badge/Framework-Laravel%2011.x-red.svg)](https://laravel.com/)
[![Security Benchmark](https://img.shields.io/badge/Benchmark-Security%20%26%20Performance-green.svg)](#test-case-matrix)
[![OWASP Top 10](https://img.shields.io/badge/OWASP-A01%20to%20A10-orange.svg)](https://owasp.org/www-project-top-ten/)

Benchmark test suite for automated code review engines on PHP / Laravel applications. This repository contains intentional security vulnerabilities, logic errors, syntax mistakes, and performance bottlenecks across typical web application components.

---

## 🎯 Benchmark Purpose

This repository validates the accuracy of automated AI/static code review engines:
1. **High Detection Rate (Recall):** Successfully identifies OWASP Top 10 vulnerabilities, N+1 query patterns, and PHP logic traps.
2. **Precision & Context Awareness:** Distinguishes between safe Eloquent constructs and raw vulnerable database queries.
3. **Consensus & Severity Calibration:** Verifies that critical flaws (RCE, SQLi, Auth Bypass) are tagged as **BLOCKING**, while performance notices remain **NON-BLOCKING**.

---

## 📋 Test Case Matrix

### 🔴 Security Vulnerabilities (OWASP Top 10)

| File | Vulnerability / Issue | Category | CWE / OWASP | Severity | Expected |
| :--- | :--- | :--- | :--- | :---: | :---: |
| `app/Http/Controllers/PostController.php` | SQL Injection via raw string interpolation (`DB::select`) | Injection | CWE-89 | High | **BLOCKING** |
| `app/Http/Controllers/PostController.php` | Mass Assignment vulnerability via unvalidated `$request->all()` | Broken Access Control | CWE-915 | High | **BLOCKING** |
| `app/Models/Post.php` | Unguarded Model (`protected $guarded = []`) | Security Misconfig | CWE-915 | High | **BLOCKING** |
| `resources/views/posts/index.blade.php` | Stored / Reflected XSS via `{!! $post->content !!}` unescaped Blade output | XSS | CWE-79 | High | **BLOCKING** |
| `resources/views/posts/show.blade.php` | Stored / Reflected XSS via `{!! $post->content !!}` unescaped Blade output | XSS | CWE-79 | High | **BLOCKING** |
| `app/Http/Controllers/TemplateController.php` | Server-Side Template Injection (SSTI) via `Blade::render()` | Injection | CWE-1336 | High | **BLOCKING** |
| `app/Http/Controllers/FileUploadController.php` | Arbitrary File Upload (missing extension & MIME validation) | File Security | CWE-434 | High | **BLOCKING** |
| `app/Services/AuthService.php` | Insecure JWT (missing expiration `exp` claim) | Broken Auth | CWE-384 | High | **BLOCKING** |
| `app/Http/Controllers/AdminController.php` | CSRF on state-changing GET request | CSRF | CWE-352 | High | **BLOCKING** |
| `app/Http/Controllers/DataController.php` | Insecure Deserialization via PHP `unserialize()` | RCE | CWE-502 | Critical | **BLOCKING** |
| `app/Http/Controllers/RedirectController.php` | Open Redirect without domain / URL validation | Redirection | CWE-601 | Medium | **BLOCKING** |
| `app/Http/Controllers/UserController.php` | Plaintext Password Storage (storing raw input without `Hash::make`) | Cryptographic | CWE-256 | High | **BLOCKING** |
| `app/Http/Controllers/CommentController.php` | IDOR on comment deletion (missing user ownership check) | Broken Access Control | CWE-639 | High | **BLOCKING** |
| `app/Http/Controllers/CommandController.php` | Shell Command Injection via \`shell_exec\`, \`passthru\`, and \`proc_open\` | Command Execution | CWE-78 | High | **BLOCKING** |
| `app/Http/Controllers/XmlController.php` | XML External Entity (XXE) Injection via \`simplexml_load_string\` and \`DOMDocument\` | Injection / XXE | CWE-611 | High | **BLOCKING** |
| `app/Http/Controllers/CookieController.php` | Insecure Cookie set with \`httpOnly: false\` and \`secure: false\` | Insecure Cookie | CWE-614 / CWE-1004 | Medium | **NON-BLOCKING** |
| `app/Http/Controllers/CorsController.php` | Permissive CORS with wildcard \`*\` origin and credentials allowed | CORS Misconfiguration | CWE-942 | High | **BLOCKING** |

### ⚠️ Logic & Syntax Errors

| File | Issue | Type | Severity | Expected |
| :--- | :--- | :--- | :---: | :---: |
| `app/Http/Controllers/PostController.php` | Assignment in conditional expression (`if ($post = $status)`) | Logic Error | High | **BLOCKING** |
| `app/Http/Controllers/PostController.php` | Null pointer dereference without object guard | Runtime Error | Medium | **NON-BLOCKING** |
| `app/Http/Controllers/ReportController.php` | Syntax Typo: misspelled keyword `retrun` instead of `return` | Syntax Error | High | **BLOCKING** |

### ⚡ Performance & Resource Management

| File | Issue | Type | Severity | Expected |
| :--- | :--- | :--- | :---: | :---: |
| `app/Services/UserStatsService.php` | N+1 Database Queries via lazy-loaded relations inside loop | Query Performance | Medium | **NON-BLOCKING** |
| `app/Services/MemoryLeakService.php` | Unbounded memory growth via static array accumulation | Memory Leak | Medium | **NON-BLOCKING** |
| `app/Services/ReportService.php` | Missing database indexes on heavily filtered query columns | Query Performance | Medium | **NON-BLOCKING** |

---

## 🚀 How to Run the Benchmark

Trigger the automated code review engine against Pull Request `#1`:

```bash
# Via GitHub CLI
gh pr view 1 --web

# Via Reviewer Trigger API
curl -X POST http://localhost:8081/api/v1/review/trigger \
  -H "Content-Type: application/json" \
  -d '{
    "repository": "IlucielI/code-review-laravel-test",
    "pull_request_id": 1
  }'
```

---

## 📊 Benchmark Validation Results

- **Total Findings Detected:** 25
- **Blocking Security Flaws:** 15 (71%)
- **Non-Blocking Performance & Quality:** 6 (29%)
- **True Positive Rate:** 100%
- **False Positive Rate:** 0%
