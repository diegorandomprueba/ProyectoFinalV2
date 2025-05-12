package com.trendfit.service;

import com.trendfit.dto.OpinionDTO;
import com.trendfit.dto.ProductRatingDTO;
import com.trendfit.exception.ResourceNotFoundException;
import com.trendfit.model.Opinion;
import com.trendfit.repository.OpinionRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;
import java.util.stream.Collectors;

@Service
public class OpinionServiceImpl implements OpinionService {

    // Mínimo de valoraciones para considerar fiable un producto
    private static final int MINIMUM_RATINGS = 5;

    @Autowired
    private OpinionRepository opinionRepository;

    @Override
    public List<OpinionDTO> getOpinionsByProductId(Long productId) {
        List<Opinion> opinions = opinionRepository.findByProductIdOrderByDateDesc(productId);
        return opinions.stream().map(this::convertToDTO).collect(Collectors.toList());
    }

    @Override
    public OpinionDTO saveOpinion(OpinionDTO opinionDTO) {
        Opinion opinion = convertToEntity(opinionDTO);
        Opinion savedOpinion = opinionRepository.save(opinion);
        return convertToDTO(savedOpinion);
    }

    @Override
    public List<ProductRatingDTO> getProductsOrderedByRating(int limit) {
        // Obtener valoración media global
        Double globalAverageRating = opinionRepository.getGlobalAverageRating();
        if (globalAverageRating == null) {
            globalAverageRating = 0.0;
        }
        
        // Obtener productos con su valoración
        List<Object[]> productRatings = opinionRepository.getProductRatings();
        List<ProductRatingDTO> productRatingDTOs = new ArrayList<>();
        
        for (Object[] result : productRatings) {
            ProductRatingDTO dto = new ProductRatingDTO();
            dto.setProductId(Long.valueOf(result[0].toString()));
            dto.setAverageRating((Double) result[1]);
            dto.setNumRatings(((Long) result[2]).intValue());
            
            // Aplicar la fórmula de la media bayesiana:
            // weighted_rating = (v / (v + m)) * R + (m / (v + m)) * C
            int numRatings = dto.getNumRatings();
            double averageRating = dto.getAverageRating();
            double weightedRating = (numRatings / (double)(numRatings + MINIMUM_RATINGS)) * averageRating + 
                                  (MINIMUM_RATINGS / (double)(numRatings + MINIMUM_RATINGS)) * globalAverageRating;
            
            dto.setWeightedRating(weightedRating);
            productRatingDTOs.add(dto);
        }
        
        // Ordenar por valoración ponderada de mayor a menor
        productRatingDTOs.sort(Comparator.comparing(ProductRatingDTO::getWeightedRating).reversed());
        
        // Limitar el número de resultados
        if (productRatingDTOs.size() > limit) {
            return productRatingDTOs.subList(0, limit);
        }
        
        return productRatingDTOs;
    }

    private OpinionDTO convertToDTO(Opinion opinion) {
        OpinionDTO dto = new OpinionDTO();
        dto.setId(opinion.getId());
        dto.setProductId(opinion.getProductId());
        dto.setUserId(opinion.getUserId());
        dto.setUserName(opinion.getUserName());
        dto.setRating(opinion.getRating());
        dto.setComment(opinion.getComment());
        dto.setDate(opinion.getDate());
        return dto;
    }

    private Opinion convertToEntity(OpinionDTO dto) {
        Opinion entity = new Opinion();
        entity.setId(dto.getId());
        entity.setProductId(dto.getProductId());
        entity.setUserId(dto.getUserId());
        entity.setUserName(dto.getUserName());
        entity.setRating(dto.getRating());
        entity.setComment(dto.getComment());
        entity.setDate(dto.getDate());
        return entity;
    }
}