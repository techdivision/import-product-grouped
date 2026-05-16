# AGENTS.md - import-product-grouped

## Zweck & Verantwortung

Das `import-product-grouped` Modul bietet **Grouped Product Import-Funktionalität** für TechDivision Commerce-Plattformen. Es ist ein **Tier 5 Modul** in der Import-Architektur und erweitert das `import-product-link` Modul mit spezialisierten Funktionen für gruppierte Produkte.

**Hauptverantwortung:**
- Grouped Product Import und Verarbeitung
- Grouped Product Relation Import (Verknüpfung von Child Products)
- Repository Pattern Implementation für persistente Speicherung
- Service Layer für Grouped-spezifische Business Logic
- Observer Pattern für Hook-Integration in der Import-Pipeline

**Modul-Kategorie:** Integration/Extension Module  
**Komplexität:** ⭐⭐⭐ (Mittel)

## Architektur & Design Patterns

### Kern-Klassen
- **GroupedProductRepository**: Persistiert Grouped Product Metadaten und Relationen
- **GroupedProductRelationRepository**: Verwaltet Beziehungen zwischen Parent und Child Products
- **GroupedProductProcessor**: Service Layer für Grouped Product Verarbeitung
- **GroupedProductObserver**: Observer für Hooks während Import-Pipeline

### Verwendete Patterns
- **Observer Pattern**: Zur Einklinken in Import-Lifecycle Events
- **Repository Pattern**: Für abstrakte Datenschicht (nicht direkte DB-Zugriffe)
- **Service Layer Pattern**: Geschäftslogik isoliert von Repositories
- **Factory Pattern**: Für Object-Erstellung und Instantiation

### Datenfluss
```
Import CSV
    ↓
Parser (import-serializer)
    ↓
Converter (import-converter)
    ↓
Grouped Product Processor
    ├─→ GroupedProductRepository
    └─→ GroupedProductRelationRepository
    ↓
Magento Database
```

## Abhängigkeiten

### Externe Pakete
- **Keine direkten PHP-Pakete**

### TechDivision Dependencies
- **import-product-link** ^26.1 - Product Link Importer (Parent)
- **import-product** ^26.2 - Base Product Importer (Indirect)
- **import-converter** - Data Conversion Framework

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-product-grouped-ee** - EE-spezifische Grouped Extensions

## Wichtige Entry Points

### Repository Klassen
```php
// Grouped Product Repository - Hauptklasse für Gruppierte Produkte
GroupedProductRepository::create($row): void
GroupedProductRepository::findByProductId($productId): GroupedProduct

// Grouped Product Relation Repository - für Product Verknüpfungen
GroupedProductRelationRepository::create($row): void
GroupedProductRelationRepository::findByParentProductId($parentId): array
```

### Service Methods
- `GroupedProductProcessor::process()` - Haupteingangspunkt
- `GroupedProductProcessor::validate()` - Validierung vor Import
- `GroupedProductProcessor::persist()` - Persistierung in Datenbank

## Events & Extension Points

**Keine Custom Events** - Tier 5 Importer-Modul nutzt Parent-Events aus import-product-link

### Observer Hooks
- `product.import.grouped.validate.pre` - Vor Validierung
- `product.import.grouped.process.post` - Nach Verarbeitung
- `product.import.grouped.persist.error` - Bei Fehler

## Database Schema

### Relevante Tabellen
- **catalog_product_link** - Speichert Grouped Product Relationen
  - `product_id` (Parent)
  - `linked_product_id` (Child)
  - `link_type_id` (Type: 3 = Grouped)

### Besonderheiten
- Grouped Products nutzen Magento Standard-Link-Tabelle
- Keine separaten Grouped-spezifischen Tabellen (nutzt catalog_product_link)
- Quantity wird in catalog_product_link_attribute gespeichert

## Common Use Cases

### Use Case 1: Grouped Products Import
```php
// CSV Dateistruktur:
// sku,grouped_sku1,grouped_qty1,grouped_sku2,grouped_qty2,...
// PROD-001,PROD-002,5,PROD-003,10

// Der Importer verarbeitet:
// 1. Validiert dass Parent Product (PROD-001) existiert
// 2. Validiert dass Child Products (PROD-002, PROD-003) existieren
// 3. Erstellt Links mit Quantities
// 4. Speichert in catalog_product_link
```

### Use Case 2: Extension Development
```php
// Eigener Processor kann nach Grouped-Import Hook einbinden
class CustomGroupedProcessor implements ProcessorInterface {
    public function process(array $data): void {
        // Hook nach Grouped Import
        $this->eventManager->dispatch('custom.grouped.process', [
            'grouped_product' => $data
        ]);
    }
}
```

## Performance Considerations

### Wichtige Performance-Aspekte
1. **Batch Processing**: Grouped Products sollten in Batches importiert werden (100-1000 pro Batch)
2. **Database Indexes**: Stelle sicher dass `catalog_product_link.product_id` und `linked_product_id` indexiert sind
3. **Relation Lookups**: Child Product IDs werden mehrfach gesucht → Caching wichtig
4. **Foreign Key Constraints**: Validiere Parent/Child Produkte vor Einfügen

### Optimierungen
- Verwende Batch-Inserts statt einzelne Operationen
- Cache Product-ID Lookups während Import
- Nutze Database Transactions für Konsistenz

## Hints für KI-Agenten

### Kritisches Verständnis
1. **Tier 5 Modul**: Spezialisierte Extension des Product Link Importers
2. **Grouped-fokussiert**: AUSSCHLIESSLICH für Grouped Product Type
3. **Observer Pattern**: Integration mit Import-Pipeline durch Hooks
4. **Repository Pattern**: Keine direkten SQL-Queries, nutze Repositories
5. **Datei-basiert**: Arbeitet mit CSV/JSON Import-Dateien

### Häufige Fehler
- ❌ Direkter DB-Zugriff statt Repository
- ❌ Grouped-Logik in anderen Product-Type Imports
- ❌ Parent/Child Validierung überspringen
- ❌ Link Quantities nicht beachten

### Best Practices
- ✅ Immer Parent und Child Products validieren
- ✅ Nutze Repository-Pattern für alle Datenzugriffe
- ✅ Implementiere Observer für Custom-Logik
- ✅ Teste mit echten CSV-Dateien

## Known Limitations

- **Product-Type spezifisch**: Funktioniert nur mit Grouped Products (type_id = grouped)
- **Abhängig von Links**: Erfordert dass Product Link Import aktiv ist
- **Keine Bundle-Support**: Kann Bundle Products nicht verarbeiten
- **Single Parent**: Ein Child kann nur zu einem Parent gehören im CSV

## Related Modules

### Direct Dependencies
- **import-product-link** - Product Link Importer (Parent)
- **import-product** - Base Product Importer

### Related/Companion Modules
- **import-product-grouped-ee** - EE-spezifische Grouped Extensions
- **import-product-bundle** - Bundle Product Importer (Alternative Grouping)
- **import-product-variant** - Configurable Product Importer

## Troubleshooting

### Problem: Grouped Products nicht importiert
**Mögliche Ursachen:**
1. Parent Product existiert nicht
2. Child Products existieren nicht
3. Link Type ID ist falsch konfiguriert

**Lösung:**
- Validiere dass alle Products bereits im System sind
- Prüfe dass Link Type ID = 3 (Grouped) ist
- Prüfe Import-Logs auf Fehler

### Problem: Quantities werden nicht importiert
**Mögliche Ursachen:**
1. Quantity-Spalten im CSV nicht korrekt benannt
2. Link Attribute nicht konfiguriert

**Lösung:**
- Verwende korrektes CSV-Format: grouped_qty1, grouped_qty2, etc.
- Prüfe dass link_attribute Tabelle initialisiert ist

## Zusammenfassung

`import-product-grouped` ist ein **Tier 5 Importer-Modul**, das spezialisierte Grouped Product Import-Funktionalität bereitstellt. Es erweitert den Product Link Importer mit Business Logic für die Verarbeitung von gruppierten Produkten aus CSV/JSON-Datenquellen.

**Für KI-Agenten:** Verstehe dieses Modul als:
- **Grouped Product Importer** mit Observer und Repository Pattern
- **Tier 5 Integration** in die generische Import-Pipeline
- **CSV-basiert** mit strukturiertem Datenfluss
- **Erweiterbar** durch Observer Hooks
