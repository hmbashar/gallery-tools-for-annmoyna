# Gallery Tools for Annmoyna

A WordPress plugin that provides tools for managing and displaying gallery events using Envira Gallery integration.

## Description

Gallery Tools for Annmoyna is a specialized WordPress plugin designed to enhance your gallery management capabilities. It creates a custom post type for gallery events and provides a flexible shortcode system to display your galleries in a grid layout with customizable styling options.

## Features

- Custom post type for gallery events
- Integration with Envira Gallery
- Customizable grid layout display
- Date-based organization
- Flexible shortcode system with multiple styling options
- Responsive design

## Installation

1. Upload the `gallery-tools-for-annmoyna` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make sure Envira Gallery plugin is installed and activated

## Usage

### Basic Shortcode

Use the following shortcode to display your gallery events:

```
[gallery_events]
```

### Available Shortcode Attributes

| Attribute | Description | Default | Example |
|-----------|-------------|---------|----------|
| posts_per_page | Number of posts to display (-1 for all) | -1 | [gallery_events posts_per_page="6"] |
| orderby | Sort posts by parameter | date | [gallery_events orderby="title"] |
| order | Sort order | DESC | [gallery_events order="ASC"] |
| link_text | Custom text for gallery link | "Click to View Photos" | [gallery_events link_text="View Gallery"] |
| post_ids | Comma-separated list of specific post IDs to display | empty | [gallery_events post_ids="1,2,3"] |

### Styling Attributes

| Attribute | Description | Example |
|-----------|-------------|----------|
| title_color | Color of the gallery title | [gallery_events title_color="#333333"] |
| title_font_size | Font size of the title | [gallery_events title_font_size="20px"] |
| item_bg_color | Background color of gallery items | [gallery_events item_bg_color="#ffffff"] |
| date_color | Color of the date text | [gallery_events date_color="#666666"] |
| date_font_size | Font size of the date | [gallery_events date_font_size="14px"] |
| hover_bg_color | Background color on hover | [gallery_events hover_bg_color="rgba(0,0,0,0.8)"] |

### Example with Multiple Attributes

```
[gallery_events
    posts_per_page="6"
    orderby="date"
    order="DESC"
    title_color="#333333"
    title_font_size="20px"
    date_color="#666666"
    link_text="View Gallery"
]
```

## Adding a New Gallery Event

1. Go to Gallery Events in your WordPress admin menu
2. Click "Add New"
3. Enter a title for your gallery event
4. Set the event date using the date picker
5. Select an Envira Gallery from the dropdown menu
6. Publish your gallery event

## Support

For support questions, bug reports, or feature requests, please use the plugin's GitHub repository.

## License

This plugin is licensed under the GPL v2 or later.

---

Connect with the author: [Facebook](https://www.facebook.com/hmbashar/)

&copy; 2025 Md Abul Bashar. All rights reserved.