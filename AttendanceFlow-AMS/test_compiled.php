<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'date',
    'value' => null,
    'onChange' => 'this.form.submit()',
    'icon' => 'calendar',
    'minDate' => null,
    'maxDate' => null,
    'placeholder' => 'Sélectionner une date',
    'formId' => null,
    'size' => 'md',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name' => 'date',
    'value' => null,
    'onChange' => 'this.form.submit()',
    'icon' => 'calendar',
    'minDate' => null,
    'maxDate' => null,
    'placeholder' => 'Sélectionner une date',
    'formId' => null,
    'size' => 'md',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = 'date_' . \Illuminate\Support\Str::random(6);
    $resolvedValue = $value ?? old('date', \Carbon\Carbon::today()->toDateString());

    $datepickerOptions = ['format' => 'yyyy-mm-dd'];
    if (!empty($minDate)) {
        $datepickerOptions['minDate'] = $minDate;
    }
    if (!empty($maxDate)) {
        $datepickerOptions['maxDate'] = $maxDate;
    }
    $datepickerJson = json_encode($datepickerOptions);

    $paddingClass = $size === 'sm' ? 'py-2 pe-8 ps-10' : 'py-2.5 pe-9 ps-10';
?>

<div class="relative w-full">
    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
        <i data-lucide="<?php echo e($icon); ?>" class="w-4 h-4 text-gray-400"></i>
    </div>

    <input
        type="text"
        id="<?php echo e($id); ?>"
        name="<?php echo e($name); ?>"
        value="<?php echo e($resolvedValue); ?>"
        data-hs-datepicker="<?php echo e($datepickerJson); ?>"
        onchange="<?php echo e($onChange); ?>"
        placeholder="<?php echo e($placeholder); ?>"
        <?php if($formId): ?> form="<?php echo e($formId); ?>" <?php endif; ?>
        class="w-full <?php echo e($paddingClass); ?> text-sm border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm dark:bg-neutral-900 dark:border-neutral-700"
    >
</div>
