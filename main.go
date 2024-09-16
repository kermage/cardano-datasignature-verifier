package main

import (
	_ "embed"
	"log"
	"os"

	"github.com/dop251/goja"
	"github.com/dop251/goja_nodejs/require"
)

//go:embed js/dist/index.js
var indexJS string

func main() {
	SCRIPT := `
	(function(require, module, exports) {
		` + indexJS + `
	})(require, module, module.exports);

	function main(args) {
		return module.exports.main(args);
	}
	`

	vm := goja.New()
	module := vm.NewObject()
	exports := vm.NewObject()

	new(require.Registry).Enable(vm)
	module.Set("exports", exports)
	vm.Set("module", module)
	vm.Set("exports", exports)
	vm.RunProgram(goja.MustCompile("index.js", SCRIPT, true))

	var main func([]string) bool

	err := vm.ExportTo(vm.Get("main"), &main)

	if err != nil {
		log.Fatal(err)
	}

	output := main(os.Args[1:])

	if output {
		os.Exit(0)
	}

	os.Exit(1)
}
