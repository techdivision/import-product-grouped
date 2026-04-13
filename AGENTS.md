# AGENTS.md - import-product-grouped

## Zweck & Verantwortung

Das `import-product-grouped` Modul bietet **Grouped Product Import-Funktionalität**. Es ist ein **Tier 5 Modul** und erweitert `import-product-link`.

**Hauptverantwortung:**
- Grouped Product Import
- Grouped Product Relation Import
- Repository Pattern für Grouped Products
- Service Layer für Grouped-Verarbeitung
- Observer Pattern für Grouped-Hooks

## Architektur & Design Patterns

### Kern-Klassen
- **GroupedProductRepository**: Persistierung von Grouped Products
- **GroupedProductRelationRepository**: Persistierung von Relations
- **GroupedProductProcessor**: Service Layer
- **GroupedProductObserver**: Observer für Hooks

### Verwendete Patterns
- **Observer Pattern**: Für Grouped-Hooks
- **Repository Pattern**: Für Daten-Persistierung
- **Service Layer**: Für Business Logic

## Abhängigkeiten

### Externe Pakete
- **Keine**

### TechDivision Dependencies
- **import-product-link** ^26.1 - Product Link Importer

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-product-grouped-ee** - EE Grouped Extensions

## Wichtige Entry Points

### Repository Klassen
```php
// Grouped Product Repository
GroupedProductRepository::create($row): void

// Grouped Product Relation Repository
GroupedProductRelationRepository::create($row): void
```

## Events & Extension Points

**Keine Events** - Tier 5 Importer-Modul

## Hints für KI-Agenten

### Wichtig zu verstehen
1. **Tier 5 Modul**: Erweitert Product Link Importer
2. **Grouped-fokussiert**: Spezialisiert auf Grouped Products
3. **Observer Pattern**: Für Hooks
4. **Repository Pattern**: Für Persistierung

## Bekannte Einschränkungen

- **Grouped-Only**: Keine anderen Product-Typen
- **Abhängig von Links**: Erfordert Product Links

## Zusammenfassung

`import-product-grouped` ist ein **Tier 5 Modul**, das Grouped Product Import-Funktionalität bietet. Es erweitert den Product Link Importer mit spezialisierter Funktionalität für Grouped Products.

**Für Agenten:** Verstehe dieses Modul als **Grouped Product Importer** mit Observer und Repository Pattern.
