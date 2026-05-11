# Changelog

All notable changes to this project will be documented in this file.

---

## [1.0.0] - 2026-04-04

### Added
- Laravel API with Sanctum authentication
- FastAPI microservice with JWT authentication
- Vendor, Product, Category, Order APIs
- Chatbot API integration

### Fixed
- Fixed JWT validation issue between Laravel and FastAPI
- Fixed "hi" matching issue in chatbot ("which" bug)

### Changed
- Refactored API response format to standard JSON structure
- Improved controller logic by moving business logic to services

### Removed
- Removed unused test routes