package com.trendfit.service;

import com.trendfit.dto.OpinionDTO;
import com.trendfit.dto.ProductRatingDTO;
import com.trendfit.model.Opinion;

import java.util.List;

public interface OpinionService {
    List<OpinionDTO> getOpinionsByProductId(Long productId);
    OpinionDTO saveOpinion(OpinionDTO opinionDTO);
    List<ProductRatingDTO> getProductsOrderedByRating(int limit);
}