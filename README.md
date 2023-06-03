![Alt text](docs/logo.png?raw=true "logo")

# Code4Nix Contao CSV Importer

## Content
This Contao bundle provides
- a backend module for the `tl_kda_product table`
- a frontend module to list the items of `tl_kda_product table`
- and a CSV importer which has to be launched by the frontend route '/import'

## Configuration
Configuration has to be done in `config/config.yaml`.

```yaml
code4nix_contao_csv_importer:
  imports:

    kda_product:
      importer: 'generic'
      source_file_path: 'files/import/sample.csv'
      #delimiter: ';'
      #enclosure: '"'
      target_table: 'tl_kda_product'
      insert_mode: 'truncate'
      file_encoding: 'ISO-8859-1' #Default to UTF-8
      #output_encoding: 'UTF-8' #Default to UTF-8
      columns:
        #column_name_contao: column_name_csv
        art_nr: 'Art.-Nr.'
        variantencode: 'Variantencode'
        weingut: 'Weingut'
        flaschengroesse: 'Flaschengröße'
        jahrgang: 'Jahrgang'
        beschreibung_1: 'Beschreibung 1'
        beschreibung_2: 'Beschreibung 2'
        oekologisch: 'Ökologisch ' # CSV Header enthält ein abschliessendes Leerzeichen -> unsauber!
        preis_pro_flasche: 'Preis/Fl. netto'
        verfuegbar: 'Verfügbar'
        lagerbestand: 'Lagerbestand'
        in_auftrag: 'In Auftrag'
        in_bestellung: 'In Bestellung'
        wareneingang_am: 'Wareneingang am'
```
