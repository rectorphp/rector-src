<?php

declare(strict_types=1);

namespace Rector\PHPOffice\ValueObject;

use Rector\Core\ValueObject\MethodName;

final class PHPExcelMethodDefaultValues
{
    /**
     * @var array<string, array<string, array<int, mixed>>>
     */
    public const METHOD_NAMES_BY_TYPE_WITH_VALUE = [
        'PHPExcel' => [
            'setHasMacros' => [false],
            'setMacrosCode' => [null],
            'setMacrosCertificate' => [null],
            'setRibbonXMLData' => [null, null],
            'setRibbonBinObjects' => [null, null],
            'getRibbonBinObjects' => ['all'],
            'getSheetByCodeName' => [''],
            'createSheet' => [null],
            'removeSheetByIndex' => [0],
            'getSheet' => [0],
            'getSheetByName' => [''],
            'setActiveSheetIndex' => [0],
            'setActiveSheetIndexByName' => [''],
            'getCellXfByIndex' => [0],
            'getCellXfByHashCode' => [''],
            'cellXfExists' => [null],
            'removeCellXfByIndex' => [0],
            'getCellStyleXfByIndex' => [0],
            'getCellStyleXfByHashCode' => [''],
            'removeCellStyleXfByIndex' => [0],
        ],
        'PHPExcel_CalcEngine_Logger' => [
            'setWriteDebugLog' => [false],
            'setEchoDebugLog' => [false],
        ],
        'PHPExcel_Calculation' => [
            'setCalculationCacheEnabled' => [true],
            'setLocale' => ['en_us'],
        ],
        'PHPExcel_Calculation_FormulaToken' => [
            'setTokenType' => ['PHPExcel_Calculation_FormulaToken::TOKEN_TYPE_UNKNOWN'],
            'setTokenSubType' => ['PHPExcel_Calculation_FormulaToken::TOKEN_SUBTYPE_NOTHING'],
        ],
        'PHPExcel_Cell' => [
            'setValue' => [null],
            'setValueExplicit' => [null, 'PHPExcel_Cell_DataType::TYPE_STRING'],
            'setCalculatedValue' => [null],
            'setDataType' => ['PHPExcel_Cell_DataType::TYPE_STRING'],
            'isInRange' => ['A1:A1'],
            'coordinateFromString' => ['A1'],
            'absoluteReference' => ['A1'],
            'absoluteCoordinate' => ['A1'],
            'splitRange' => ['A1:A1'],
            'rangeBoundaries' => ['A1:A1'],
            'rangeDimension' => ['A1:A1'],
            'getRangeBoundaries' => ['A1:A1'],
            'columnIndexFromString' => ['A'],
            'stringFromColumnIndex' => [0],
            'extractAllCellReferencesInRange' => ['A1'],
            'setXfIndex' => [0],
        ],
        'PHPExcel_Cell_DataType' => [
            'checkString' => [null],
            'checkErrorCode' => [null],
        ],
        'PHPExcel_Cell_DataValidation' => [
            'setFormula1' => [''],
            'setFormula2' => [''],
            'setType' => ['PHPExcel_Cell_DataValidation::TYPE_NONE'],
            'setErrorStyle' => ['PHPExcel_Cell_DataValidation::STYLE_STOP'],
            'setOperator' => [''],
            'setAllowBlank' => [false],
            'setShowDropDown' => [false],
            'setShowInputMessage' => [false],
            'setShowErrorMessage' => [false],
            'setErrorTitle' => [''],
            'setError' => [''],
            'setPromptTitle' => [''],
            'setPrompt' => [''],
        ],
        'PHPExcel_Cell_DefaultValueBinder' => [
            'dataTypeForValue' => [null],
        ],
        'PHPExcel_Cell_Hyperlink' => [
            'setUrl' => [''],
            'setTooltip' => [''],
        ],
        'PHPExcel_Chart' => [
            'setPlotVisibleOnly' => [true],
            'setDisplayBlanksAs' => ['0'],
            'setTopLeftOffset' => [null, null],
            'setBottomRightOffset' => [null, null],
        ],
        'PHPExcel_Chart_DataSeries' => [
            'setPlotType' => [''],
            'setPlotGrouping' => [null],
            'setPlotDirection' => [null],
            'setPlotStyle' => [null],
            'setSmoothLine' => [true],
        ],
        'PHPExcel_Chart_DataSeriesValues' => [
            'setDataType' => ['PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER'],
            'setDataSource' => [null, true],
            'setPointMarker' => [null],
            'setFormatCode' => [null],
            'setDataValues' => [[], true],
        ],
        'PHPExcel_Chart_Layout' => [
            MethodName::CONSTRUCT => [[]],
        ],
        'PHPExcel_Chart_Legend' => [
            'setPosition' => ['PHPExcel_Chart_Legend::POSITION_RIGHT'],
            'setPositionXL' => ['PHPExcel_Chart_Legend::XL_LEGEND_POSITION_RIGHT'],
            'setOverlay' => [false],
        ],
        'PHPExcel_Chart_PlotArea' => [
            MethodName::CONSTRUCT => [null, []],
            'setPlotSeries' => [[]],
        ],
        'PHPExcel_Chart_Title' => [
            'setCaption' => [null],
        ],
        'PHPExcel_Comment' => [
            'setAuthor' => [''],
            'setWidth' => ['96pt'],
            'setHeight' => ['55.5pt'],
            'setMarginLeft' => ['59.25pt'],
            'setMarginTop' => ['1.5pt'],
            'setVisible' => [false],
            'setAlignment' => ['Style\\Alignment::HORIZONTAL_GENERAL'],
        ],
        'PHPExcel_DocumentProperties' => [
            'setCreator' => [''],
            'setLastModifiedBy' => [''],
            'setCreated' => [null],
            'setModified' => [null],
            'setTitle' => [''],
            'setDescription' => [''],
            'setSubject' => [''],
            'setKeywords' => [''],
            'setCategory' => [''],
            'setCompany' => [''],
            'setManager' => [''],
        ],
        'PHPExcel_DocumentSecurity' => [
            'setLockRevision' => [false],
            'setLockStructure' => [false],
            'setLockWindows' => [false],
            'setRevisionsPassword' => ['', false],
            'setWorkbookPassword' => ['', false],
        ],
        'PHPExcel_HashTable' => [
            'addFromSource' => [null],
            'getIndexForHashCode' => [''],
            'getByIndex' => [0],
            'getByHashCode' => [''],
        ],
        'PHPExcel_IOFactory' => [
            'addSearchLocation' => ['', '', ''],
            'createReader' => [''],
        ],
        'PHPExcel_NamedRange' => [
            'setName' => [null],
            'setRange' => [null],
            'setLocalOnly' => [false],
        ],
        'PHPExcel_Reader_Abstract' => [
            'setReadDataOnly' => [false],
            'setReadEmptyCells' => [true],
            'setIncludeCharts' => [false],
            'setLoadSheetsOnly' => [null],
        ],
        'PHPExcel_Reader_CSV' => [
            'setInputEncoding' => ['UTF-8'],
            'setDelimiter' => [','],
            'setEnclosure' => ['\\'],
            'setSheetIndex' => [0],
            'setContiguous' => [false],
        ],
        'PHPExcel_Reader_Excel2003XML' => [
            'parseRichText' => [''],
        ],
        'PHPExcel_Reader_Excel2007' => [
            'parseRichText' => [null],
            'boolean' => [null],
        ],
        'PHPExcel_Reader_Excel2007_Chart' => [
            'parseRichText' => [null],
        ],
        'PHPExcel_Reader_Excel2007_Theme' => [
            'getColourByIndex' => [0],
        ],
        'PHPExcel_Reader_Excel5' => [
            'parseRichText' => [''],
        ],
        'PHPExcel_Reader_Gnumeric' => [
            'parseRichText' => [''],
        ],
        'PHPExcel_Reader_HTML' => [
            'setInputEncoding' => ['ANSI'],
            'setSheetIndex' => [0],
        ],
        'PHPExcel_Reader_OOCalc' => [
            'parseRichText' => [''],
        ],
        'PHPExcel_Reader_SYLK' => [
            'setInputEncoding' => ['ANSI'],
            'setSheetIndex' => [0],
        ],
        'PHPExcel_RichText' => [
            'createText' => [''],
            'createTextRun' => [''],
            'setRichTextElements' => [null],
        ],
        'PHPExcel_RichText_TextElement' => [
            'setText' => [''],
        ],
        'PHPExcel_Settings' => [
            'setLocale' => ['en_us'],
            'setLibXmlLoaderOptions' => [null],
        ],
        'PHPExcel_Shared_CodePage' => [
            'numberToName' => ['1252'],
        ],
        'PHPExcel_Shared_Date' => [
            'excelToDateTimeObject' => [0, null],
            'excelToTimestamp' => [0, null],
            'PHPToExcel' => [0],
            'timestampToExcel' => [0],
            'isDateTimeFormatCode' => [''],
            'stringToExcel' => [''],
        ],
        'PHPExcel_Shared_Drawing' => [
            'pixelsToEMU' => [0],
            'EMUToPixels' => [0],
            'pixelsToPoints' => [0],
            'pointsToPixels' => [0],
            'degreesToAngle' => [0],
            'angleToDegrees' => [0],
        ],
        'PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer' => [
            'setSpgr' => [false],
            'setStartCoordinates' => ['A1'],
            'setStartOffsetX' => [0],
            'setStartOffsetY' => [0],
            'setEndCoordinates' => ['A1'],
            'setEndOffsetX' => [0],
            'setEndOffsetY' => [0],
        ],
        'PHPExcel_Shared_File' => [
            'setUseUploadTempDirectory' => [false],
        ],
        'PHPExcel_Shared_Font' => [
            'setAutoSizeMethod' => ['PHPExcel_Shared_Font::AUTOSIZE_METHOD_APPROX'],
            'setTrueTypeFontPath' => [''],
            'fontSizeToPixels' => ['11'],
            'inchSizeToPixels' => ['1'],
            'centimeterSizeToPixels' => ['1'],
        ],
        'PHPExcel_Shared_OLE' => [
            'localDateToOLE' => [null],
        ],
        'PHPExcel_Shared_PasswordHasher' => [
            'hashPassword' => [''],
        ],
        'PHPExcel_Shared_String' => [
            'controlCharacterOOXML2PHP' => [''],
            'controlCharacterPHP2OOXML' => [''],
            'isUTF8' => [''],
            'substring' => ['', 0, 0],
            'strToUpper' => [''],
            'strToLower' => [''],
            'strToTitle' => [''],
            'strCaseReverse' => [''],
            'setDecimalSeparator' => ['.'],
            'setThousandsSeparator' => [','],
            'setCurrencyCode' => ['$'],
            'SYLKtoUTF8' => [''],
        ],
        'PHPExcel_Style' => [
            'applyFromArray' => [null, true],
            'setConditionalStyles' => [null],
        ],
        'PHPExcel_Style_Alignment' => [
            'applyFromArray' => [null],
            'setHorizontal' => ['PHPExcel_Style_Alignment::HORIZONTAL_GENERAL'],
            'setVertical' => ['PHPExcel_Style_Alignment::VERTICAL_BOTTOM'],
            'setTextRotation' => [0],
            'setWrapText' => [false],
            'setShrinkToFit' => [false],
            'setIndent' => [0],
            'setReadorder' => [0],
        ],
        'PHPExcel_Style_Border' => [
            'applyFromArray' => [null],
            'setBorderStyle' => ['PHPExcel_Style_Border::BORDER_NONE'],
        ],
        'PHPExcel_Style_Borders' => [
            'applyFromArray' => [null],
            'setDiagonalDirection' => ['PHPExcel_Style_Borders::DIAGONAL_NONE'],
        ],
        'PHPExcel_Style_Color' => [
            'applyFromArray' => [null],
            'setARGB' => ['PHPExcel_Style_Color::COLOR_BLACK'],
            'setRGB' => ['000000'],
        ],
        'PHPExcel_Style_Conditional' => [
            'setConditionType' => ['PHPExcel_Style_Conditional::CONDITION_NONE'],
            'setOperatorType' => ['PHPExcel_Style_Conditional::OPERATOR_NONE'],
            'setText' => [null],
            'addCondition' => [''],
        ],
        'PHPExcel_Style_Fill' => [
            'applyFromArray' => [null],
            'setFillType' => ['PHPExcel_Style_Fill::FILL_NONE'],
            'setRotation' => [0],
        ],
        'PHPExcel_Style_Font' => [
            'applyFromArray' => [null],
            'setName' => ['Calibri'],
            'setSize' => ['10'],
            'setBold' => [false],
            'setItalic' => [false],
            'setSuperScript' => [false],
            'setSubScript' => [false],
            'setUnderline' => ['PHPExcel_Style_Font::UNDERLINE_NONE'],
            'setStrikethrough' => [false],
        ],
        'PHPExcel_Style_NumberFormat' => [
            'applyFromArray' => [null],
            'setFormatCode' => ['PHPExcel_Style_NumberFormat::FORMAT_GENERAL'],
            'setBuiltInFormatCode' => [0],
            'toFormattedString' => ['0', 'PHPExcel_Style_NumberFormat::FORMAT_GENERAL', null],
        ],
        'PHPExcel_Style_Protection' => [
            'applyFromArray' => [null],
            'setLocked' => ['PHPExcel_Style_Protection::PROTECTION_INHERIT'],
            'setHidden' => ['PHPExcel_Style_Protection::PROTECTION_INHERIT'],
        ],
        'PHPExcel_Worksheet' => [
            'getChartByIndex' => [null],
            'getChartByName' => [''],
            'setTitle' => ['Worksheet', true],
            'setSheetState' => ['PHPExcel_Worksheet::SHEETSTATE_VISIBLE'],
            'setCellValue' => ['A1', null, false],
            'setCellValueByColumnAndRow' => [0, '1', null, false],
            'setCellValueExplicit' => ['A1', null, 'PHPExcel_Cell_DataType::TYPE_STRING', false],
            'setCellValueExplicitByColumnAndRow' => [0, '1', null, 'PHPExcel_Cell_DataType::TYPE_STRING', false],
            'getCell' => ['A1', true],
            'getCellByColumnAndRow' => [0, '1', true],
            'cellExists' => ['A1'],
            'cellExistsByColumnAndRow' => [0, '1'],
            'getRowDimension' => ['1', true],
            'getColumnDimension' => ['A', true],
            'getColumnDimensionByColumn' => [0],
            'getStyle' => ['A1'],
            'getConditionalStyles' => ['A1'],
            'conditionalStylesExists' => ['A1'],
            'removeConditionalStyles' => ['A1'],
            'getStyleByColumnAndRow' => [0, '1', null, null],
            'setBreak' => ['A1', 'PHPExcel_Worksheet::BREAK_NONE'],
            'setBreakByColumnAndRow' => [0, '1', 'PHPExcel_Worksheet::BREAK_NONE'],
            'mergeCells' => ['A1:A1'],
            'mergeCellsByColumnAndRow' => [0, '1', 0, '1'],
            'unmergeCells' => ['A1:A1'],
            'unmergeCellsByColumnAndRow' => [0, '1', 0, '1'],
            'setMergeCells' => [[]],
            'protectCells' => ['A1', '', false],
            'protectCellsByColumnAndRow' => [0, '1', 0, '1', '', false],
            'unprotectCells' => ['A1'],
            'unprotectCellsByColumnAndRow' => [0, '1', 0, '1', '', false],
            'setAutoFilterByColumnAndRow' => [0, '1', 0, '1'],
            'freezePane' => [''],
            'freezePaneByColumnAndRow' => [0, '1'],
            'insertNewRowBefore' => ['1', '1'],
            'insertNewColumnBefore' => ['A', '1'],
            'insertNewColumnBeforeByIndex' => [0, '1'],
            'removeRow' => ['1', '1'],
            'removeColumn' => ['A', '1'],
            'removeColumnByIndex' => [0, '1'],
            'setShowGridlines' => [false],
            'setPrintGridlines' => [false],
            'setShowRowColHeaders' => [false],
            'setShowSummaryBelow' => [true],
            'setShowSummaryRight' => [true],
            'setComments' => [[]],
            'getComment' => ['A1'],
            'getCommentByColumnAndRow' => [0, '1'],
            'setSelectedCell' => ['A1'],
            'setSelectedCells' => ['A1'],
            'setSelectedCellByColumnAndRow' => [0, '1'],
            'setRightToLeft' => [false],
            'fromArray' => [null, null, 'A1', false],
            'rangeToArray' => ['A1', null, true, true, false],
            'namedRangeToArray' => ['', null, true, true, false],
            'getHyperlink' => ['A1'],
            'setHyperlink' => ['A1', null],
            'hyperlinkExists' => ['A1'],
            'getDataValidation' => ['A1'],
            'setDataValidation' => ['A1', null],
            'dataValidationExists' => ['A1'],
            'setCodeName' => [null],
        ],
        'PHPExcel_Worksheet_AutoFilter' => [
            'setRange' => [''],
            'getColumnByOffset' => [0],
            'shiftColumn' => [null, null],
        ],
        'PHPExcel_Worksheet_AutoFilter_Column' => [
            'setFilterType' => ['PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_FILTERTYPE_FILTER'],
            'setJoin' => ['PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_COLUMN_JOIN_OR'],
            'setAttributes' => [[]],
            'addRule' => [
                1 => true,
            ],
        ],
        'PHPExcel_Worksheet_AutoFilter_Column_Rule' => [
            'setRuleType' => ['PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_RULETYPE_FILTER'],
            'setValue' => [''],
            'setOperator' => ['PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_EQUAL'],
            'setGrouping' => [null],
            'setRule' => ['PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_EQUAL', '', null],
        ],
        'PHPExcel_Worksheet_BaseDrawing' => [
            'setName' => [''],
            'setDescription' => [''],
            'setCoordinates' => ['A1'],
            'setOffsetX' => [0],
            'setOffsetY' => [0],
            'setWidth' => [0],
            'setHeight' => [0],
            'setWidthAndHeight' => [0, 0],
            'setResizeProportional' => [true],
            'setRotation' => [0],
        ],
        'PHPExcel_Worksheet_CellIterator' => [
            'setIterateOnlyExistingCells' => [true],
        ],
        'PHPExcel_Worksheet_ColumnDimension' => [
            'setWidth' => ['-1'],
            'setAutoSize' => [false],
        ],
        'PHPExcel_Worksheet_Drawing' => [
            'setPath' => ['', true],
        ],
        'PHPExcel_Worksheet_Drawing_Shadow' => [
            'setVisible' => [false],
            'setBlurRadius' => ['6'],
            'setDistance' => ['2'],
            'setDirection' => [0],
            'setAlignment' => [0],
            'setAlpha' => [0],
        ],
        'PHPExcel_Worksheet_HeaderFooter' => [
            'setDifferentOddEven' => [false],
            'setDifferentFirst' => [false],
            'setScaleWithDocument' => [true],
            'setAlignWithMargins' => [true],
        ],
        'PHPExcel_Worksheet_HeaderFooterDrawing' => [
            'setName' => [''],
            'setOffsetX' => [0],
            'setOffsetY' => [0],
            'setWidth' => [0],
            'setHeight' => [0],
            'setWidthAndHeight' => [0, 0],
            'setResizeProportional' => [true],
            'setPath' => ['', true],
        ],
        'PHPExcel_Worksheet_MemoryDrawing' => [
            'setImageResource' => [null],
            'setRenderingFunction' => ['PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT'],
            'setMimeType' => ['PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT'],
        ],
        'PHPExcel_Worksheet_PageSetup' => [
            'setPaperSize' => ['PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER'],
            'setOrientation' => ['PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT'],
            'setScale' => ['100', true],
            'setFitToPage' => [true],
            'setFitToHeight' => ['1', true],
            'setFitToWidth' => ['1', true],
            'setColumnsToRepeatAtLeft' => [null],
            'setColumnsToRepeatAtLeftByStartAndEnd' => ['A', 'A'],
            'setRowsToRepeatAtTop' => [null],
            'setRowsToRepeatAtTopByStartAndEnd' => ['1', '1'],
            'setHorizontalCentered' => [false],
            'setVerticalCentered' => [false],
            'setFirstPageNumber' => [null],
        ],
        'PHPExcel_Worksheet_Protection' => [
            'setSheet' => [false],
            'setObjects' => [false],
            'setScenarios' => [false],
            'setFormatCells' => [false],
            'setFormatColumns' => [false],
            'setFormatRows' => [false],
            'setInsertColumns' => [false],
            'setInsertRows' => [false],
            'setInsertHyperlinks' => [false],
            'setDeleteColumns' => [false],
            'setDeleteRows' => [false],
            'setSelectLockedCells' => [false],
            'setSort' => [false],
            'setAutoFilter' => [false],
            'setPivotTables' => [false],
            'setSelectUnlockedCells' => [false],
            'setPassword' => ['', false],
        ],
        'PHPExcel_Worksheet_RowDimension' => [
            'setRowHeight' => ['-1'],
            'setZeroHeight' => [false],
        ],
        'PHPExcel_Worksheet_SheetView' => [
            'setZoomScale' => ['100'],
            'setZoomScaleNormal' => ['100'],
            'setView' => [null],
        ],
        'PHPExcel_Writer_Abstract' => [
            'setIncludeCharts' => [false],
            'setPreCalculateFormulas' => [true],
            'setUseDiskCaching' => [false, null],
        ],
        'PHPExcel_Writer_CSV' => [
            'save' => [null],
            'setDelimiter' => [','],
            'setEnclosure' => ['\\'],
            'setLineEnding' => [PHP_EOL],
            'setUseBOM' => [false],
            'setIncludeSeparatorLine' => [false],
            'setExcelCompatibility' => [false],
            'setSheetIndex' => [0],
            'writeLine' => [null, null],
        ],
        'PHPExcel_Writer_Excel2007' => [
            'getWriterPart' => [''],
            'save' => [null],
            'setOffice2003Compatibility' => [false],
        ],
        'PHPExcel_Writer_Excel2007_ContentTypes' => [
            'getImageMimeType' => [''],
        ],
        'PHPExcel_Writer_Excel2007_StringTable' => [
            'writeStringTable' => [null],
            'flipStringTable' => [[]],
        ],
        'PHPExcel_Writer_Excel5' => [
            'save' => [null],
        ],
        'PHPExcel_Writer_Excel5_Workbook' => [
            'writeWorkbook' => [null],
        ],
        'PHPExcel_Writer_Excel5_Worksheet' => [
            'writeBIFF8CellRangeAddressFixed' => ['A1'],
        ],
        'PHPExcel_Writer_HTML' => [
            'save' => [null],
            'setSheetIndex' => [0],
            'setGenerateSheetNavigationBlock' => [true],
            'setImagesRoot' => ['.'],
            'setEmbedImages' => [true],
            'setUseInlineCss' => [false],
        ],
        'PHPExcel_Writer_OpenDocument' => [
            'getWriterPart' => [''],
            'save' => [null],
        ],
        'PHPExcel_Writer_PDF' => [
            'save' => [null],
        ],
        'PHPExcel_Writer_PDF_Core' => [
            'setPaperSize' => ['PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER'],
            'setOrientation' => ['PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT'],
            'setTempDir' => [''],
            'prepareForSave' => [null],
        ],
        'PHPExcel_Writer_PDF_DomPDF' => [
            'save' => [null],
        ],
        'PHPExcel_Writer_PDF_mPDF' => [
            'save' => [null],
        ],
        'PHPExcel_Writer_PDF_tcPDF' => [
            'save' => [null],
        ],
    ];
}
