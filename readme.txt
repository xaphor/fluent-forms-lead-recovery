# Fluent Forms Lead Recovery

**Rescue abandoned form submissions by sending partial entries via webhook to your CRM or marketing automation tool.**

## Description

Fluent Forms Lead Recovery captures abandoned form submissions (partial entries) from your Fluent Forms and sends them to your CRM, email marketing tool, or any other system via webhooks. This allows you to follow up with potential leads who started filling out your forms but didn't complete them.

Think of it like abandoned cart recovery, but for forms!

### Key Features

- **Capture Partial Submissions**: Automatically capture form data when users abandon your forms
- **Webhook Integration**: Send partial submission data to any CRM or marketing tool that accepts webhooks
- **Form-Specific Settings**: Configure different settings for each form
- **Data Control**: Choose what data to send for each form
- **Developer-Friendly**: Extensive hooks and filters for customization
- **Debug Mode**: Troubleshoot webhook connections with detailed logging
- **Test Tool**: Verify your webhook configuration before going live

### Use Cases

- Follow up with potential leads who abandoned registration forms
- Re-engage users who started but didn't complete contact forms
- Analyze which form fields cause users to abandon your forms
- Implement progressive profiling by saving partial data for returning users
- Trigger personalized email campaigns based on partial form data

## Installation

1. Upload the `fluent-forms-lead-recovery` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Fluent Forms > Lead Recovery to configure the plugin

## Requirements

- WordPress 5.0 or higher
- Fluent Forms 4.0 or higher
- PHP 7.0 or higher

## Configuration

### Global Settings

1. Navigate to Fluent Forms > Lead Recovery
2. Enter your global webhook URL (this can be overridden for individual forms)
3. Enable debug mode if you want to log webhook requests and responses
4. Select which forms to enable lead recovery for

### Form-Specific Settings

1. Navigate to Fluent Forms > All Forms
2. Edit the form you want to configure
3. Go to Settings > Lead Recovery
4. Enable lead recovery for the form
5. Configure form-specific settings:
   - Webhook URL (optional, overrides the global URL)
   - Data to send (all form data, only filled fields, or custom fields)

## Webhook Data Format

The webhook sends a JSON payload with the following structure:

```json
{
  "submission_type": "partial",
  "submission_id": 123,
  "form_id": 5,
  "form_title": "Contact Form",
  "timestamp": 1646837400,
  "date_created": "2023-03-09 12:30:00",
  "fields": {
    "email": {
      "label": "Email Address",
      "value": "example@example.com"
    },
    "name": {
      "label": "Full Name",
      "value": "John Doe"
    }
  },
  "meta": {
    "user_ip": "192.168.1.1",
    "user_agent": "Mozilla/5.0...",
    "referrer": "https://example.com/contact",
    "site_url": "https://example.com",
    "form_url": "https://example.com/contact"
  }
}
```

## Developer Hooks

### Actions

- `fflr/loaded` - Fires when the plugin is loaded
- `fflr/init` - Fires when the plugin is initialized
- `fflr/activated` - Fires when the plugin is activated
- `fflr/deactivated` - Fires when the plugin is deactivated
- `fflr/webhook_success` - Fires after a successful webhook request
- `fflr/webhook_failure` - Fires after a failed webhook request

### Filters

- `fflr/webhook_data` - Modify the data sent to the webhook
- `fflr/form_enabled` - Determine if lead recovery is enabled for a form
- `fflr/webhook_url` - Modify the webhook URL for a form

## Frequently Asked Questions

### Will this work with any Fluent Forms form?

Yes, Fluent Forms Lead Recovery works with any form created with Fluent Forms.

### Can I send the partial submissions to multiple destinations?

Yes, you can use the `fflr/webhook_data` filter to modify the webhook behavior and send data to multiple destinations.

### How can I customize the data format sent to my CRM?

You can use the `fflr/webhook_data` filter to modify the data structure before it's sent to your webhook endpoint.

### Is this compatible with Fluent Forms Pro?

Yes, this plugin works with both the free and pro versions of Fluent Forms.

## Changelog

### 1.0.0
* Initial release

## Upgrade Notice

### 1.0.0
Initial release

## License

This plugin is licensed under the GPL v3 or later.

## Credits

Fluent Forms Lead Recovery was created by [Your Name].

## Support

For support, please create an issue on the [GitHub repository](https://github.com/yourusername/fluent-forms-lead-recovery/issues).