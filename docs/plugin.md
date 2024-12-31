The **Vehicle Management Plugin** is an example plugin that uses the `cpt-meta` library to create and manage a custom post type for vehicles. It demonstrates how to leverage the power of the library to define meta boxes, handle metadata, and integrate custom REST API endpoints.

## Features

- **Custom Post Type for Vehicles**:
  - Manage vehicles with a custom post type that supports titles, editor, thumbnails, and custom fields.
  - Includes a taxonomy for "Vehicle Types."

- **Dynamic Meta Box**:
  - Custom fields for vehicle name, description, type, top speed, and electric status.
  - Automatically sanitizes and saves field values.

- **Admin Enhancements**:
  - Custom columns in the WordPress admin list for "Type" and "Top Speed."
  - Sortable columns for better management.

- **REST API Integration**:
  - Custom REST API endpoint to fetch vehicle data (`/wp-json/vehicle/v1/custom-data`).

- **Default Metadata**:
  - Automatically assigns default values for fields like top speed and type.


## Installation

1. Clone or download this repository into the `wp-content/plugins/` directory of your WordPress installation.
2. Install the `cpt-meta` library via Composer:
   ```shell
   composer require devuri/cpt-meta-box
   ```
3. Activate the plugin from the WordPress admin dashboard.


## Usage

### Adding Vehicles
- Go to **Vehicles** in the WordPress admin dashboard.
- Add a new vehicle and fill in the details in the "Vehicle Details" meta box.

### Viewing Data
- Custom columns for "Type" and "Top Speed" are visible in the admin list for Vehicles.

### Accessing REST API
- Fetch all vehicle data using the REST endpoint:
  ```
  GET /wp-json/vehicle/v1/custom-data
  ```

## Example Use Case

The plugin is ideal for managing a fleet of vehicles, with metadata for each vehicle's type, speed, and whether it is electric. It demonstrates how to use the `cpt-meta` library to:
- Create structured content management solutions.
- Enhance WordPress admin experiences.
- Integrate with external systems via the REST API.

## Customizations

You can extend this plugin to:
- Add more custom fields to the "Vehicle Details" meta box.
- Define additional taxonomies for vehicles (e.g., fuel type, brand).
- Enhance REST API endpoints for more advanced use cases.

> Feel free to use and adapt it to your needs!

### Features of This Plugin

1. **Custom Post Type for Vehicles**:
   - Supports titles, editor, thumbnails, and custom fields.
   - Includes a hierarchical taxonomy for "Vehicle Type."

2. **Dynamic Meta Box**:
   - Fields include vehicle name, description, type, top speed, and a checkbox for electric vehicles.
   - Sanitizes and saves custom field values.

3. **Admin Column Enhancements**:
   - Custom columns for "Type" and "Top Speed."
   - Sortable columns for easier management.

4. **REST API Integration**:
   - Custom REST endpoint (`/wp-json/vehicle/v1/custom-data`) returns vehicle metadata in JSON format.

5. **Default Metadata**:
   - Assigns default values for top speed and type if not provided.


### Installation

1. Save the file as `vehicle-management-plugin.php` in the `wp-content/plugins/` directory.
2. Install the `cpt-meta` library using Composer:
   ```shell
   composer require devuri/cpt-meta-box
   ```
3. Activate the plugin from the WordPress admin dashboard.

### Usage

- **Create a Vehicle**: Add a new "Vehicle" from the WordPress admin.
- **Manage Metadata**: Fill out the fields in the "Vehicle Details" meta box.
- **REST API**: Access vehicle data via the endpoint `/wp-json/vehicle/v1/custom-data`.
