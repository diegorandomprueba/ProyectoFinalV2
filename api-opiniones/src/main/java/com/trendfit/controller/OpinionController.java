package com.trendfit.controller;

import com.trendfit.dto.OpinionDTO;
import com.trendfit.dto.ProductRatingDTO;
import com.trendfit.service.OpinionService;
import io.swagger.annotations.Api;
import io.swagger.annotations.ApiOperation;
import io.swagger.annotations.ApiParam;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import javax.validation.Valid;
import java.util.List;

@RestController
@RequestMapping("/api")
@CrossOrigin(origins = "*")
@Api(value = "API de Opiniones", description = "Operaciones relacionadas con las opiniones de productos")
public class OpinionController {

    @Autowired
    private OpinionService opinionService;

    @ApiOperation(value = "Obtener opiniones de un producto por su ID")
    @GetMapping("/opinions/{productId}")
    public ResponseEntity<List<OpinionDTO>> getOpinions(
            @ApiParam(value = "ID del producto del que se quieren obtener las opiniones", required = true)
            @PathVariable Long productId) {
        List<OpinionDTO> opinions = opinionService.getOpinionsByProductId(productId);
        return new ResponseEntity<>(opinions, HttpStatus.OK);
    }

    @ApiOperation(value = "Guardar una nueva opinión sobre un producto")
    @PostMapping("/opinions")
    public ResponseEntity<OpinionDTO> sendOpinion(
            @ApiParam(value = "Datos de la opinión", required = true)
            @Valid @RequestBody OpinionDTO opinionDTO) {
        OpinionDTO savedOpinion = opinionService.saveOpinion(opinionDTO);
        return new ResponseEntity<>(savedOpinion, HttpStatus.CREATED);
    }

    @ApiOperation(value = "Obtener productos ordenados por valoración (usando Bayesian Average)")
    @GetMapping("/ratings")
    public ResponseEntity<List<ProductRatingDTO>> getRatings(
            @ApiParam(value = "Límite de resultados a retornar", defaultValue = "10")
            @RequestParam(defaultValue = "10") int limit) {
        List<ProductRatingDTO> ratings = opinionService.getProductsOrderedByRating(limit);
        return new ResponseEntity<>(ratings, HttpStatus.OK);
    }
}