# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository Purpose

This repository contains the OpenAPI 3.1.0 specification for Checkeeper API v3. Checkeeper provides check mailing and PDF generation services through a REST API.

## API Structure

### Base Information
- API Version: 3.0.0
- Base URL: `https://api.checkeeper.com/v3`
- Authentication: Token-based (HTTP Bearer)
- Specification Format: OpenAPI 3.1.0

### Core Endpoints

#### Check Operations
- `GET /checks` - List checks with filtering
- `POST /check` - Create new check(s)
- `GET /check/{check}/status` - Get check status
- `GET /check/{check}/tracking` - Get tracking events
- `POST /check/{check}/cancel` - Cancel a check
- `GET /check/{check}/image` - Get check image
- `POST /check/{check}/attachment` - Add attachment
- `GET /check/{check}/voucher/image` - Get voucher image

#### Team & Template Operations
- `GET /team/info` - Get team information
- `GET /templates` - List available templates

### Key Filtering Capabilities

Filtering uses query parameters in format: `/v3/checks?filters[field][operator]=value`

**Operators:**
- `$eq` - Equals
- `$ne` - Not Equal
- `$lt`, `$lte`, `$gt`, `$gte` - Comparison
- `$in`, `$notIn` - List matching
- `$contains` - String matching
- `$between` - Range
- `$or`, `$and` - Logical operations

**Filterable Fields:**
- Check identifiers: `id`, `request_id`, `template_id`
- Status: `status`, `ship_method`, `test`
- Check details: `number`, `date`, `amount`, `memo`, `note`
- Parties: `payer_line1-4`, `payee_line1-4`
- Metadata: `meta`, `created`, `updated`

### Response Schemas

**Standard Responses:**
- `201 Created` - Successful check creation with `checks`, `existing`, `total_credits`
- `200 OK` - Successful retrieval
- `401 Unauthorized` - Authentication failure
- `403 Forbidden` - Authorization failure
- `422 Unprocessable Entity` - Validation errors
- `404 Not Found` - Resource not found

**Error Structure:**
All errors return `message` field. Validation errors include `errors` object with field-specific messages.

## Working with This Specification

### Modifying the Spec
- Maintain OpenAPI 3.1.0 compliance
- Keep all `operationId` values unique and descriptive (kebab-case)
- Include both request/response examples where applicable
- Update version in `info.version` for breaking changes

### Adding New Endpoints
1. Add path under `paths` object
2. Define `operationId`, `summary`, `tags`
3. Specify request body schema (if applicable)
4. Define all response codes with schemas
5. Reference common responses via `$ref` where possible

### Schema Management
- Common schemas live in `components.schemas`
- Common responses in `components.responses`
- Use `$ref` for reusable components
- Include descriptions for all properties

### Tags
Current tags: `Check`, `Team`, `Template`

## Important Notes

- API is completely decoupled from Checkeeper Application (app.checkeeper.com)
- Checks/templates created via API do not appear in the web application
- No current rate limiting implemented
- Webhooks available for real-time status updates (configured in admin panel)
